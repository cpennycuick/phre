<?php

namespace PHRE\DataSource;

interface DataSource {

	public function hasNext();

	public function next();

	/**
	 * @return DataRecord
	 */
	public function current();

	/**
	 * @return DataRecord
	 */
	public function peek();

	public function reset();

}
