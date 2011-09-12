<?php

class base_recorddetail extends base_record
{
	protected $id;
	protected $created;
	protected $datamodel_name = '';
	protected $required_fields = array();


	public function __construct()
	{
		parent::__construct();
		$this->set_created(date('Y-m-d H:i:s', time()));
	}

	public function get_id()
	{
		return $this->id;
	}
	public function set_id($val)
	{
		$this->id = $val;
	}

	public function get_created()
	{
		return $this->created;
	}
	public function set_created($v)
	{
		$this->created = $v;
	}

	public function save()
	{
		$status = $this->validate();
		if($status)
		{
			$model = new $this->datamodel_name;
			$result = $model->save($this);

			if(!empty($result))
			{
				$this->set_id($result);
			}
			else
			{
				$this->throw_notice('There was a problem with saving '
					. get_class($this)
					. '. Could not find the record after saving it');

				$status = false;
			}
		}

		return $status;
	}

	public function delete()
	{
		$model = new $this->datamodel_name;
		$result = $model->delete($this);
		unset($this);
		return $result;
	}

	protected function validate()
	{
		$valid = true;
		$notice_snippet = '';

		foreach($this->required_fields as $field)
		{
			if(is_null($this->$field))
			{
				$notice_snippet .= $field . ', ';
				$valid = false;
			}
		}

		if(!$valid)
		{
			$this->throw_notice("__CLASS__: Required fields for were missing. "
				. "Please fill in ".rtrim($notice_snippet, ', '));
		}

		return $valid;
	}
}

?>
