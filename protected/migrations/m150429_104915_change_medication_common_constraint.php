<?php

class m150429_104915_change_medication_common_constraint extends OEMigration
{
	public function up()
	{
		$this->dropOETable('medication_common', true);

		$this->createOETable(
			'medication_common',
			array(
				'id' => 'pk',
				'medication_id' => 'int(11) not null',
				'CONSTRAINT `medication_common_medication_drug_id_fk` FOREIGN KEY (`medication_id`) REFERENCES `medication_drug` (`id`)',
				'CONSTRAINT `medication_common_medication_id_uq` UNIQUE (`medication_id`)',
			),
			true
		);

	}

	public function down()
	{

		$this->dropOETable('medication_common', true);

		$this->createOETable(
			'medication_common',
			array(
				'id' => 'pk',
				'medication_id' => 'int(11) not null',
			),
			true
		);
	}
}