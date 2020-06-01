<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Checkin
{
    /** @var ?Location */
    protected $arrival;

    /** @var ?Location */
    protected $departure;

    /**
     * Checkin constructor.
     * @param Location $arrival
     */
    public function __construct(Location $arrival)
    {
        $this->arrival = $arrival;
    }

    /**
     * @param Collection $data
     * @return Collection
     */
    public static function hydrate(Collection $data): Collection
    {
        $data = $data->groupBy('DirectumID')->values()->map(function (Collection $c){
            return $c->sortBy('id')->values();
        })->flatten();
        $data = $data->split(ceil($data->count() / 2));

        return $data->map(function (Collection $c) {
            $checkin = new static($c->get(0));

            if ($dep = $c->get(1))
                $checkin->setDeparture($dep);

            return $checkin;
        });
    }

    /**
     * @return Location|null
     */
    public function getArrival(): ?Location
    {
        return $this->arrival;
    }

    /**
     * @param Location $arrival
     * @return $this
     */
    public function setArrival(Location $arrival)
    {
        $this->arrival = $arrival;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeparture(): ?Location
    {
        return $this->departure;
    }

    /**
     * @param Location $departure
     * @return $this
     */
    public function setDeparture(Location $departure)
    {
        $this->departure = $departure;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->getArrival()->account;
    }

    /**
     * @return Carbon
     */
    public function getArrivedAt(): Carbon
    {
        return Carbon::parse($this->getArrival()->Date)->addHours(3);
    }

    /**
     * @return Carbon|null
     */
    public function getDepartedAt(): ?Carbon
    {
        if ($this->departure){
            return Carbon::parse($this->getDeparture()->Date)->addHours(3);
        }

        return null;
    }

    /**
     * @return \DateInterval
     */
    public function getDuration(): \DateInterval
    {
        if ($this->getDeparture()){
            return $this->getDepartedAt()->diff($this->getArrivedAt());
        }

        return now()->addHours(3)->diff($this->getArrivedAt());
    }

    /**
     * @return bool
     */
    public function isCheckedOut()
    {
        return ! is_null($this->getDeparture());
    }
}
