<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;

interface Renderable {

	public function render(DataSource $data);

}
