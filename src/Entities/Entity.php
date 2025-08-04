<?php

namespace Jengo\Core\Entities;

use CodeIgniter\Entity\Entity as BaseEntity;

class Entity extends BaseEntity
{
    /**
     * Fields you always want included in output arrays/json
     * (e.g. computed or extra fields like encrypted_id)
     */
    protected array $includedFields = [];

    /**
     * Fields you want excluded when exporting
     * (e.g. password, secret tokens)
     */
    protected array $excludedFields = [];

    /**
     * Toggle whether to use includedFields or excludedFields logic
     */
    protected bool $useIncludedFields = true;

    public function getEncryptedId(): ?string
    {
        if (!isset($this->attributes['id'])) {
            return null;
        }

        return encrypt($this->attributes['id']);
    }

    /**
     * Add an included field dynamically (chainable)
     */
    public function includeField(string $field): static
    {
        $this->includedFields[] = $field;
        return $this;
    }

    /**
     * Exclude a field dynamically (chainable)
     */
    public function excludeField(string $field): static
    {
        $this->excludedFields[] = $field;
        return $this;
    }

    /**
     * Return only selected fields
     */
    public function only(array $fields): array
    {
        return array_intersect_key($this->toArray(), array_flip($fields));
    }

    /**
     * Convert to array for display/API
     */
    public function toArray(bool $onlyChanged = false, bool $cast = true, bool $recursive = false): array
    {
        $array = parent::toArray($onlyChanged, $cast, $recursive);

        // Add virtual field
        if (isset($this->attributes['id'])) {
            $array['encrypted_id'] = $this->getEncryptedId();
        }

        // Apply include/exclude filtering
        if ($this->useIncludedFields && !empty($this->includedFields)) {
            return array_intersect_key($array, array_flip($this->includedFields));
        }

        if (!$this->useIncludedFields && !empty($this->excludedFields)) {
            return array_diff_key($array, array_flip($this->excludedFields));
        }

        return $array;
    }

    /**
     * For automatic json_encode($entity)
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * Switch to using excludedFields logic
     */
    public function useExcludedFields(bool $state = true): static
    {
        $this->useIncludedFields = !$state;
        return $this;
    }
}
