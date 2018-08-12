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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastAction()
    {
        return $this->hasOne(Action::class, 'id', 'last_interaction_id');
    }

    /**
     * @param $query
     */
    public function scopeWithLastAction($query)
    {
        $query->addSubSelect('last_interaction_id', Action::select('id')
            ->whereRaw('customer_id = customers.id')
            ->latest()
        )->with('lastAction');
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

    /**
     * @param $query
     */
    public function scopeOrderByCompany($query)
    {
        $query->orderBySub(Company::select('name')->whereRaw('customers.company_id = companies.id'));
    }

    /**
     * @param $query
     */
    public function scopeOrderByBirthday($query)
    {
        $query->orderBy('birth_date');
    }

    /**
     * @param $query
     */
    public function scopeOrderByLastActionDate($query)
    {
        $query->orderBySubDesc(Action::select('created_at')->whereRaw('customers.id = actions.customer_id')->latest());
    }

    /**
     * @param $query
     * @param $field
     */
    public function scopeOrderByField($query, $field)
    {
        if ($field === 'name') {
            $query->orderByName();
        } elseif ($field === 'company') {
            $query->orderByCompany();
        } elseif ($field === 'birthday') {
            $query->orderByBirthday();
        } elseif ($field === 'last_action') {
            $query->orderByLastActionDate();
        }
    }

    /**
     * @param $query
     * @param $search
     */
    public function scopeWhereSearch($query, $search)
    {
        foreach (explode(' ', $search) as $term) {
            $query->where(function ($query) use ($term) {
                $query->where('first_name', 'like', '%'.$term.'%')
                    ->orWhere('last_name', 'like', '%'.$term.'%')
                    ->orWhereHas('company', function ($query) use ($term) {
                        $query->where('name', 'like', '%'.$term.'%');
                    });
            });
        }
    }
}