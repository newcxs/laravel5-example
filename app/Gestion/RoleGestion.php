<?php namespace App\Gestion;

use App\Models\Role;

class RoleGestion {

	/**
	 * The Role instance.
	 *
	 * @var App\Models\Role
	 */
	protected $role;

	/**
	 * Create a new RolegGestion instance.
	 *
	 * @param  App\Models\Role $role
	 * @return void
	 */
	public function __construct(Role $role)
	{
		$this->role = $role;
	}

	/**
	 * Get all roles.
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function all()
	{
		return $this->role->all();
	}

	/**
	 * Update roles.
	 *
	 * @param  array  $inputs
	 * @return void
	 */
	public function update($inputs)
	{
		foreach ($inputs as $key => $value)
		{
			$role = $this->role->where('slug', $key)->firstOrFail();
			$role->titre = $value;
			$role->save();
		}
	}

}
