<?php

namespace App\Traits;

use App\Exceptions\DatabaseOperationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Str;

trait ExceptionTrait
{
    /**
     * Throws an exception when no specific model is found for a given ID.
     *
     * @param mixed $identifier The identifier (typically ID).
     * @throws NotFoundHttpException
     */
    protected function throwModelNotFoundException($identifier)
    {
        throw new NotFoundHttpException("No " . $this->getModelSingularName() . " found with ID $identifier");
    }

    /**
     * Throws an exception when fetching models fails.
     *
     * @throws DatabaseOperationException
     */
    protected function throwFetchModelsException()
    {
        throw new DatabaseOperationException("Error fetching " . $this->getModelPluralName());
    }

    /**
     * Throws an exception when creating a model fails.
     *
     * @throws DatabaseOperationException
     */
    protected function throwCreateModelException()
    {
        throw new DatabaseOperationException("Error creating " . $this->getModelSingularName());
    }

    /**
     * Throws an exception when updating a model fails.
     *
     * @param mixed $identifier The identifier (typically ID).
     * @throws DatabaseOperationException
     */
    protected function throwUpdateModelException($identifier)
    {
        throw new DatabaseOperationException("Error updating " . $this->getModelSingularName() . " with ID $identifier");
    }

    /**
     * Throws an exception when deleting a model fails.
     *
     * @param mixed $identifier The identifier (typically ID).
     * @throws DatabaseOperationException
     */
    protected function throwDeleteModelException($identifier)
    {
        throw new DatabaseOperationException("Error deleting " . $this->getModelSingularName() . " with ID $identifier");
    }

    /**
     * General method to throw an exception for database operations.
     *
     * @param string $action The action attempted (e.g., 'fetching', 'updating', 'deleting').
     * @param mixed $identifier Optional identifier like ID or name.
     * @throws DatabaseOperationException
     */
    protected function throwDatabaseOperationException($action, $identifier = null)
    {
        $message = "Error $action " . $this->getModelSingularName();
        if ($identifier !== null) {
            $message .= " with ID $identifier";
        }

        throw new DatabaseOperationException($message);
    }

    /**
     * Helper method to get the singular form of the model name.
     *
     * @return string
     */
    protected function getModelSingularName()
    {
        return strtolower(class_basename($this->model)) ?? 'resource';
    }

    /**
     * Helper method to get the plural form of the model name.
     *
     * @return string
     */
    protected function getModelPluralName()
    {
        return Str::plural($this->getModelSingularName());
    }
}
