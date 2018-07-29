<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * @package App
 */
class Customer extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date',
        'last_action_date' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    /**
     * @param $query
     */
    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithLastActionDate($query)
    {
        $subQuery = \DB::table('actions')
            ->select('created_at')
            ->whereRaw('customer_id = customers.id')
            ->latest()
            ->limit(1);

        return $query->select('customers.*')->selectSub($subQuery, 'last_action_date');
    }

    /**
     * @param $query
     */
    public function scopeWithLastActionType($query)
    {
        $query->addSubSelect('last_action_type', Action::select('type')
            ->whereRaw('customer_id = customers.id')
            ->latest()
        );
    }
}