<?php

/** @noinspection ALL */

declare(strict_types=1);

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * @template TModel of Model
 */
abstract class BaseRepository
{
    /**
     * @var TModel
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @param TModel $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return TModel|null
     */
    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @return Collection<int, TModel>
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @return Collection<int, TModel>
     */
    public function getByAttribute(string $field, string|int|bool $attribute): Collection
    {
        /** @var Collection<int, TModel> */
        return $this->model->where($field, $attribute)->get();
    }

    /**
     * @return Model|null
     */
    public function getFirstByAttribute(string $field, string|int|bool $attribute)
    {
        return $this->model->where($field, $attribute)->first();
    }

    /**
     * Update rows filtered by attribute = value with provided data
     */
    public function updateByAttribute(array $data, string $attribute, mixed $value): bool
    {
        return (bool) $this->model->where($attribute, $value)->update($data);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return TModel
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $model = $this->findOrFail($id);
        return $model->update($data);
    }

    public function storeMany(array $data): bool
    {
        try {
            foreach ($data as $item) {
                $this->model->create($item);
            }

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage(), [
                'class' => self::class,
                'method' => __METHOD__,
            ]);

            return false;
        }
    }

    public function updateById(array $data, int $id): bool
    {
        return (bool) $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): ?bool
    {
        return (bool) $this->model->where('id', $id)->delete();
    }

    /**
     * @return Model|null
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     * @return TModel|bool
     */
    public function save(array|Model $data)
    {
        if (is_array($data)) {
            return $this->saveArrayData($data);
        }

        $modelClass = get_class($this->model);
        if (! $data instanceof $modelClass) {
            throw new InvalidArgumentException("Object passed to save must be an instance of {$modelClass}");
        }

        return $this->saveObjectData($data);
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * @return TModel|null
     */
    public function find(int|string $id)
    {
        return $this->model->find($id);
    }

    /**
     * @return TModel
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int|string $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @return TModel
     */
    private function saveArrayData(array $data)
    {
        $key = $this->model->getKeyName();

        if (isset($data[$key])) {
            /** @var ?Model $found */
            $found = $this->model->find($data[$key]);

            if ($found) {
                $this->model = $found;
            }
        }

        $this->model->fill($data);
        $this->model->save();

        return $this->model;
    }

    /**
     * @param  Model  $data
     *
     * @return TModel
     */
    private function saveObjectData(Model $data)
    {
        $this->model = $data;
        $this->model->save();

        return $this->model;
    }
}
