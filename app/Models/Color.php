<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Color extends Model {
    protected $fillable = ['name']; // Penting!
    public $timestamps = false;    // Penting agar tidak error 'updated_at'
}