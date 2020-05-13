<?php

namespace Hyyppa\Filemaker;

use Carbon\Carbon;
use Hyyppa\Filemaker\Connector\FilemakerConnector;
use Hyyppa\Filemaker\Connector\QueryString;
use Hyyppa\Filemaker\Contracts\FilemakerInterface;
use Hyyppa\Filemaker\Contracts\FilemakerModel;
use Hyyppa\Filemaker\Exception\FilemakerException;
use Hyyppa\Filemaker\Model\ModelCollection;
use Hyyppa\Filemaker\Payload\FieldPayload;
use Hyyppa\Filemaker\Payload\FilePayload;
use Hyyppa\Filemaker\Payload\ModelPayload;
use Hyyppa\Filemaker\Payload\Payload;
use Hyyppa\Filemaker\Payload\SearchPayload;
use Hyyppa\Filemaker\Record\Record;
use Hyyppa\Filemaker\Record\RecordCollection;
use Ramsey\Uuid\Uuid;

/**
 * Class FilemakerRepository.
 */
class FilemakerRepository extends FilemakerConnector implements FilemakerInterface
{
    /**
     * @param FilemakerModel|string $model
     * @param                       $search
     *
     * @return FilemakerModel|null
     * @throws FilemakerException
     */
    public function find($model, $search = null): ?FilemakerModel
    {
        $this->assertModelExists($model);

        $payload = new SearchPayload(
            $this->makeSearchParams($model, $search)
        );

        try {
            $response = $this->postRequest($this->findUrl($model), $payload)
                             ->dot('response.data');
        } catch (FilemakerException $e) {
            if ($e->getCode() === FILEMAKER_NO_MATCH) {
                return null;
            }

            throw $e;
        }

        if (! $response) {
            return null;
        }

        /** @var FilemakerModel $model */
        $model = ( new RecordCollection($model, $response) )->first()->toModel();

        $model->filemakerLoad($this);

        return $model;
    }

    /**
     * @param FilemakerModel|string $model
     * @param int                   $limit
     *
     * @return RecordCollection
     * @throws FilemakerException
     */
    public function records(string $model, int $limit = 100): RecordCollection
    {
        $this->assertModelExists($model);

        $params = ( new QueryString() )
            ->offset(1)
            ->limit($limit);

        $response = $this->getRequest($this->recordsUrl($model), $params)
                         ->dot('response.data');

        return new RecordCollection($model, $response);
    }

    /**
     * @param string|FilemakerModel $model
     * @param int                   $limit
     *
     * @return ModelCollection
     * @throws FilemakerException
     */
    public function recordModels(string $model, int $limit = 100): ModelCollection
    {
        $records = $this->records($model::tableName(), $limit);

        $models = new ModelCollection();

        $records->each(function (Record $record) use ($models) {
            $models->push($record->toModel());
        });

        return $models;
    }

    /**
     * @param FilemakerModel|string $model
     * @param                       $search
     *
     * @return array
     * @throws FilemakerException
     */
    public function query($model, $search): array
    {
        $this->assertModelExists($model);
        $search = $this->makeSearchParams($model, $search);

        $payload = new SearchPayload($search);

        $response = $this->postRequest($this->findUrl($model), $payload)
                         ->dot('response.data');

        return $response;
    }

    /**
     * @param FilemakerModel $model
     *
     * @return FilemakerInterface
     * @throws FilemakerException
     */
    public function save(FilemakerModel $model): FilemakerInterface
    {
        if ($this->update($model, $model->queryString())) {
            return $this;
        }

        $payload = $this->modelPayload($model);
        $this->postRequest($this->recordsUrl($model), $payload)
             ->dot('response.recordId');

        $model->filemakerSave($this);

        return $this;
    }

    /**
     * @param FilemakerModel $model
     * @param                $search
     *
     * @return bool
     * @throws FilemakerException
     */
    public function update(FilemakerModel $model, $search = null): bool
    {
        try {
            $needs_update = $this->needsUpdate($model);
        } catch (FilemakerException $e) {
            if ($e->getCode() !== FILEMAKER_NO_MATCH) {
                throw $e;
            }

            return false;
        }

        if (! $needs_update) {
            return true;
        }

        $old = $this->query($model, $search);
        $id = $old[0]->recordId;

        $payload = $this->modelPayload($model);

        $this->patchRequest($this->recordsUrl($model, $id), $payload)
             ->dot('response.modId');

        $model->filemakerUpdate($this);

        return true;
    }

    /**
     * @param FilemakerModel $model
     *
     * @return bool
     * @throws FilemakerException
     */
    public function needsUpdate(FilemakerModel $model): bool
    {
        $remote = $this->find($model, $model->queryString());

        if (! $remote) {
            throw new FilemakerException('no model', FILEMAKER_NO_MATCH);
        }

        return $remote->updated_at < $model->updated_at;
    }

    /**
     * @param string|FilemakerModel $model
     *
     * @throws FilemakerException
     *
     * @return FilemakerModel|null
     */
    public function latest($model): ?FilemakerModel
    {
        $this->assertModelExists($model);

        $params = ( new QueryString() )
            ->sortDescending('updated_at')
            ->limit(1);

        $response = $this->getRequest($this->recordsUrl($model), $params)
                         ->dot('response.data');

        if (! $latestRecord = ( new RecordCollection($model, $response) )->first()) {
            return null;
        }

        return $latestRecord->toModel();
    }

    /**
     * @param $model
     *
     * @return Carbon|null
     * @throws FilemakerException
     */
    public function lastUpdate($model): ?Carbon
    {
        if (! $latest = $this->latest($model)) {
            return null;
        }

        return $latest->updated_at;
    }

    /**
     * @param FilemakerModel $model
     * @param string         $field
     * @param                $filename
     *
     * @throws FilemakerException
     */
    public function addFileToRecord(FilemakerModel $model, string $field, $filename): void
    {
        $record = $this->query($model, $model->id);
        $id = $record[0]->recordId;

        if (! $filename instanceof FilePayload) {
            $payload = new FilePayload($filename);
        } else {
            $payload = $filename;
        }

        $this->postRequest($this->containerUrl($model, $field, $id), $payload);
    }

    /**
     * @param FilemakerModel $model
     * @param                $search
     *
     * @return bool
     * @throws FilemakerException
     */
    public function delete(FilemakerModel $model, $search): bool
    {
        $record = $this->query($model, $search);
        $path = $this->recordsUrl($model, $record[0]->recordId);

        return 0 === $this->deleteRequest($path)->dot('response.modId');
    }

    /**
     * @param FilemakerModel $model
     *
     * @return bool
     * @throws FilemakerException
     */
    public function exists(FilemakerModel $model): bool
    {
        try {
            $this->query($model, $model->queryString());
        } catch (FilemakerException $e) {
            if ($e->getCode() === FILEMAKER_NO_MATCH) {
                return false;
            }

            throw $e;
        }

        return true;
    }

    /**
     * @param FilemakerModel|string $model
     * @param                       $search
     *
     * @return array
     */
    protected function makeSearchParams($model, $search): array
    {
        if (! $model instanceof FilemakerModel) {
            $model = new $model;
        }

        if (! \is_array($search)) {
            return [
                $model->getFilemakerIndex() => (string) $search,
            ];
        }

        if ($search === null) {
            $index = $model->getFilemakerIndex();

            return [
                $index => (string) $model->$index,
            ];
        }

        return $search;
    }

    /**
     * @param $model
     *
     * @return FilemakerInterface
     * @throws FilemakerException
     */
    protected function assertModelExists($model): FilemakerInterface
    {
        if (! $model instanceof FilemakerModel && ! class_exists($model)) {
            throw new FilemakerException(sprintf('Model [%s] does not exist', $model));
        }

        return $this;
    }

    /**
     * @param FilemakerModel $model
     *
     * @return Payload
     * @throws FilemakerException
     */
    protected function modelPayload(FilemakerModel $model): Payload
    {
        return new ModelPayload($model, $this->getFields($model));
    }

    /**
     * @param FilemakerModel $model
     *
     * @return array
     * @throws FilemakerException
     * @throws \Exception
     */
    protected function getFields(FilemakerModel $model): array
    {
        if (isset($this->fields[$model->getFilemakerTable()])) {
            return $this->fields[$model->getFilemakerTable()];
        }

        $id = (string) Uuid::uuid4();

        $payload = new FieldPayload([
            $model->fm_unique[0] => $id,
        ]);

        $this->postRequest($this->recordsUrl($model), $payload);
        $record = $this->query($model, [$model->fm_unique[0] => $id]);
        $path = $this->recordsUrl($model, $record[0]->recordId);
        $this->deleteRequest($path);

        return $this->fields[$model->getFilemakerTable()] = array_keys(
            get_object_vars($record[0]->fieldData)
        );
    }
}
