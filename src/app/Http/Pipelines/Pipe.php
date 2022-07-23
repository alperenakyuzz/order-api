<?php

namespace App\Http\Pipelines;

use Closure;

interface Pipe
{
	public function handle($order, Closure $next);
}