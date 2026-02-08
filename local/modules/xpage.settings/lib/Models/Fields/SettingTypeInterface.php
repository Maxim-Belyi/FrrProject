<?php
declare(strict_types=1);

namespace Xpage\Settings\Models\Fields;

use CAdminForm;

interface SettingTypeInterface
{
	public function getValue();

	public function draw(CAdminForm $form): void;
}