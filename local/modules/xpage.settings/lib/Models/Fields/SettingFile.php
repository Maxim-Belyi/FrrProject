<?php
declare(strict_types=1);

namespace Xpage\Settings\Models\Fields;

use CAdminForm;
use CFile;
use JsonException;
use Xpage\Settings\Abstracts\Setting;
use Xpage\Settings\Models\SettingFieldType;

class SettingFile extends Setting implements SettingTypeInterface
{
	public static function add(array $params): void
	{
		$fileId = CFile::SaveFile($params['value'], 'modules/xpage.settings');
		$params['value'] = $fileId;
		self::getTableEntity()::add(array_merge(['field_type' => SettingFieldType::getByCode('file')->getId()], $params));
	}


	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->getValue();
	}

	/**
	 * @return string
	 */
	public function getValue(): int
	{
		return (int)$this->get('value');
	}

	public function draw(CAdminForm $form): void
	{
		$form->AddFileField(
			$this->getCode(),
			$this->getName(). " ({$this->getCode()})",
			$this->getValue(),
			[
				'iMaxW' => 100,
				'iMaxH' => 100,
			]
		);
	}

	/**
	 * @throws JsonException|JsonException
	 */
	private function getJSFileParams(): string
	{
		$fileId = $this->getValue();
		$code = $this->getCode();
		$fileExists = $fileId && CFile::GetPath($fileId) && file_exists($_SERVER['DOCUMENT_ROOT'] . CFile::GetPath($fileId));
		$filePath = $this->getPath();
		$filesize = 0;
		$fileName = null;
		if (!empty($filePath))
		{
			$filesize = filesize($filePath);
			$fileName = basename($filePath);
		}

		return json_encode([
			'id'               => 'bx_file_' . $code,
			'fileExists'       => $fileExists,
			'files'            => [
				0 => [
					'ID'             => $fileId,
					'TIMESTAMP_X'    => '',
					'HEIGHT'         => '0',
					'WIDTH'          => '0',
					'FILE_SIZE'      => $filesize,
					'FILE_NAME'      => $fileName,
					'ORIGINAL_NAME'  => $fileName,
					'DESCRIPTION'    => '',
					'HANDLER_ID'     => '',
					'EXTERNAL_ID'    => '',
					'SRC'            => $filePath,
					'FORMATED_SIZE'  => $filesize ? round($filesize / 1000 / 1000, 2) . ' мб' : '',
					'IS_IMAGE'       => false,
					'FILE_NOT_FOUND' => !$fileExists,
				],
			],
			'menuNew'          => [
				0 => [
					'ID'             => 'upload',
					'GLOBAL_ICON'    => 'adm-menu-upload-pc',
					'TEXT'           => 'Загрузить с компьютера',
					'CLOSE_ON_CLICK' => false,
				],
			],
			'menuExist'        => [
				0 => [
					'ID'             => 'upload',
					'GLOBAL_ICON'    => 'adm-menu-upload-pc',
					'TEXT'           => 'Заменить файлом с компьютера',
					'CLOSE_ON_CLICK' => false,
				],
			],
			'multiple'         => false,
			'useUpload'        => true,
			'useMedialib'      => false,
			'useFileDialog'    => false,
			'useCloud'         => false,
			'delName'          => 'delete_file_' . $code,
			'descName'         => '',
			'inputSize'        => '50',
			'minPreviewHeight' => '50',
			'minPreviewWidth'  => '50',
			'showDesc'         => false,
			'showDel'          => true,
			'maxCount'         => false,
			'viewMode'         => false,
			'inputs'           => [
				'upload'      => [
					'NAME' => $code,
				],
				'medialib'    => false,
				'file_dialog' => false,
				'cloud'       => false,
			],
		], JSON_THROW_ON_ERROR);
	}

	public function getPath(): string
	{
		return (string)CFile::GetPath($this->getValue());
	}

}