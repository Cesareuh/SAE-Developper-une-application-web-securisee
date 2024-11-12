<?php

namespace iutnc\nrv\render;

abstract class Renderer{
	// protected const int COMPACT = 1;
	// protected const int LONG = 2;
	abstract public function render(int $selector):string;
}