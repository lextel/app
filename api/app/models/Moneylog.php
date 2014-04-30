<?php


class Moneylog extends Eloquent  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member_moneylogs';

    protected $fillable = ['id', 'phase_id', 'total', 'sum', 
                            'type', 'source', 'member_id', 'created_at', 
                            'updated_at'];

    public $timestamps = false;    
}
