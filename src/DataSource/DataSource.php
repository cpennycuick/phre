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

	/**
	 * @return DataGroup
	 */
	public function startGroup();

	/**
	 * @return DataGroup
	 */
	public function group();

}
