<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SecretariaController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('secretaria');
  }

  public function index()
  {
    return view('home/homeSecretaria');
  }
}
