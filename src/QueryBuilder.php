<?php

namespace NadLambino\QueryBuilder;

use ArrayAccess;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\ForwardsCalls;
use NadLambino\QueryBuilder\Sources\CollectionSource;
use NadLambino\QueryBuilder\Sources\RequestSource;
use NadLambino\QueryBuilder\Sources\SourceInterface;
use NadLambino\QueryBuilder\Concerns\AddsFieldsToQuery;
use NadLambino\QueryBuilder\Concerns\AddsIncludesToQuery;
use NadLambino\QueryBuilder\Concerns\FiltersQuery;
use NadLambino\QueryBuilder\Concerns\SortsQuery;

/**
 * @mixin EloquentBuilder
 */
class QueryBuilder implements ArrayAccess
{
    use FiltersQuery;
    use SortsQuery;
    use AddsIncludesToQuery;
    use AddsFieldsToQuery;
    use ForwardsCalls;

    protected SourceInterface $source;

    public function __construct(
        protected EloquentBuilder|Relation $subject,
        Request|Collection|array|null $source = null
    ) {
        $this->source = match (true) {
            is_null($source)                => app(RequestSource::class),
            $source instanceof Collection,
            is_array($source)               => CollectionSource::make($source),
            $source instanceof Request      => RequestSource::make($source),
            default                         => RequestSource::make($source),
        };

        AllowedFilter::setSource($this->source);
    }

    public function getEloquentBuilder(): EloquentBuilder
    {
        if ($this->subject instanceof EloquentBuilder) {
            return $this->subject;
        }

        return $this->subject->getQuery();
    }

    public function getSubject(): Relation|EloquentBuilder
    {
        return $this->subject;
    }

    public static function for(
        EloquentBuilder|Relation|string $subject,
        Request|Collection|array|null $source = null
    ): static {
        if (is_subclass_of($subject, Model::class)) {
            $subject = $subject::query();
        }

        return new static($subject, $source);
    }

    public function __call($name, $arguments)
    {
        $result = $this->forwardCallTo($this->subject, $name, $arguments);

        /*
         * If the forwarded method call is part of a chain we can return $this
         * instead of the actual $result to keep the chain going.
         */
        if ($result === $this->subject) {
            return $this;
        }

        return $result;
    }

    public function clone(): static
    {
        return clone $this;
    }

    public function __clone()
    {
        $this->subject = clone $this->subject;
    }

    public function __get($name)
    {
        return $this->subject->{$name};
    }

    public function __set($name, $value)
    {
        $this->subject->{$name} = $value;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->subject[$offset]);
    }

    public function offsetGet($offset): bool
    {
        return $this->subject[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->subject[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->subject[$offset]);
    }
}
