<?php

namespace Hyyppa\Filemaker\Contracts;

interface EloquentInterface
{
    /**
     * Fill the model with an array of attributes.
     *
     * @param  array $attributes
     *
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes);

    /**
     * Fill the model with an array of attributes. Force mass assignment.
     *
     * @param  array $attributes
     *
     * @return $this
     */
    public function forceFill(array $attributes);

    /**
     * Qualify the given column name by the model's table.
     *
     * @param  string $column
     *
     * @return string
     */
    public function qualifyColumn($column);

    /**
     * Create a new instance of the given model.
     *
     * @param  array $attributes
     * @param  bool  $exists
     *
     * @return static
     */
    public function newInstance($attributes = [], $exists = false);

    /**
     * Create a new model instance that is existing.
     *
     * @param  array       $attributes
     * @param  string|null $connection
     *
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null);

    /**
     * Eager load relations on the model.
     *
     * @param  array|string $relations
     *
     * @return $this
     */
    public function load($relations);

    /**
     * Eager load relations on the model if they are not already eager loaded.
     *
     * @param  array|string $relations
     *
     * @return $this
     */
    public function loadMissing($relations);

    /**
     * Update the model in the database.
     *
     * @param  array $attributes
     * @param  array $options
     *
     * @return bool
     */
    public function update(array $attributes = [], array $options = []);

    /**
     * Save the model and all of its relationships.
     *
     * @return bool
     */
    public function push();

    /**
     * Save the model to the database.
     *
     * @param  array $options
     *
     * @return bool
     */
    public function save(array $options = []);

    /**
     * Save the model to the database using transaction.
     *
     * @param  array $options
     *
     * @return bool
     *
     * @throws \Throwable
     */
    public function saveOrFail(array $options = []);

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete();

    /**
     * Force a hard delete on a soft deleted model.
     *
     * This method protects developers from running forceDelete when trait is missing.
     *
     * @return bool|null
     */
    public function forceDelete();

    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery();

    /**
     * Get a new query builder that doesn't have any global scopes or eager loading.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newModelQuery();

    /**
     * Get a new query builder with no relationships loaded.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQueryWithoutRelationships();

    /**
     * Register the global scopes for this builder instance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function registerGlobalScopes($builder);

    /**
     * Get a new query builder that doesn't have any global scopes.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newQueryWithoutScopes();

    /**
     * Get a new query instance without a given scope.
     *
     * @param  \Illuminate\Database\Eloquent\Scope|string $scope
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQueryWithoutScope($scope);

    /**
     * Get a new query to restore one or more models by their queueable IDs.
     *
     * @param  array|int $ids
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQueryForRestoration($ids);

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query);

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array $models
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = []);

    /**
     * Create a new pivot model instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  array                               $attributes
     * @param  string                              $table
     * @param  bool                                $exists
     * @param  string|null                         $using
     *
     * @return \Illuminate\Database\Eloquent\Relations\Pivot
     */
    public function newPivot(\Illuminate\Database\Eloquent\Model $parent, array $attributes, $table, $exists, $using = null);

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Convert the model instance to JSON.
     *
     * @param  int $options
     *
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = 0);

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize();

    /**
     * Reload a fresh model instance from the database.
     *
     * @param  array|string $with
     *
     * @return static|null
     */
    public function fresh($with = []);

    /**
     * Reload the current model instance with fresh attributes from the database.
     *
     * @return $this
     */
    public function refresh();

    /**
     * Clone the model into a new, non-existing instance.
     *
     * @param  array|null $except
     *
     * @return static
     */
    public function replicate(array $except = null);

    /**
     * Determine if two models have the same ID and belong to the same table.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null $model
     *
     * @return bool
     */
    public function is($model);

    /**
     * Determine if two models are not the same.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null $model
     *
     * @return bool
     */
    public function isNot($model);

    /**
     * Get the database connection for the model.
     *
     * @return \Illuminate\Database\Connection
     */
    public function getConnection();

    /**
     * Get the current connection name for the model.
     *
     * @return string
     */
    public function getConnectionName();

    /**
     * Set the connection associated with the model.
     *
     * @param  string $name
     *
     * @return $this
     */
    public function setConnection($name);

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable();

    /**
     * Set the table associated with the model.
     *
     * @param  string $table
     *
     * @return $this
     */
    public function setTable($table);

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName();

    /**
     * Set the primary key for the model.
     *
     * @param  string $key
     *
     * @return $this
     */
    public function setKeyName($key);

    /**
     * Get the table qualified key name.
     *
     * @return string
     */
    public function getQualifiedKeyName();

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType();

    /**
     * Set the data type for the primary key.
     *
     * @param  string $type
     *
     * @return $this
     */
    public function setKeyType($type);

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing();

    /**
     * Set whether IDs are incrementing.
     *
     * @param  bool $value
     *
     * @return $this
     */
    public function setIncrementing($value);

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Get the queueable identity for the entity.
     *
     * @return mixed
     */
    public function getQueueableId();

    /**
     * Get the queueable relationships for the entity.
     *
     * @return array
     */
    public function getQueueableRelations();

    /**
     * Get the queueable connection for the entity.
     *
     * @return mixed
     */
    public function getQueueableConnection();

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey();

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName();

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed $value
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value);

    /**
     * Get the default foreign key name for the model.
     *
     * @return string
     */
    public function getForeignKey();

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage();

    /**
     * Set the number of models to return per page.
     *
     * @param  int $perPage
     *
     * @return $this
     */
    public function setPerPage($perPage);

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset);

    /**
     * Get the value for a given offset.
     *
     * @param  mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset);

    /**
     * Set the value for a given offset.
     *
     * @param  mixed $offset
     * @param  mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value);

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset);
}
