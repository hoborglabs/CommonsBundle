<?php
require_once __DIR__ . '/../../../../../../application/Commons/conf/init.php';

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__."/Entities"));
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$connectionOptions = array(
	'dbname' => 'hoborg_dev_cmns',
	'user' => 'hoborg_dev',
	'password' => 'hoborg',
	'host' => 'localhost',
	'driver' => 'pdo_mysql',
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);

$cli = new \Symfony\Component\Console\Application('Doctrine Command Line Interface', Doctrine\Common\Version::VERSION);
$cli->setCatchExceptions(true);
$helperSet = $cli->getHelperSet();
foreach ($helpers as $name => $helper) {
	$helperSet->set($helper, $name);
}
$cli->addCommands(array(
	// DBAL Commands
	new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
	new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

	// ORM Commands
	new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
	new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
	new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
	new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
	new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
	new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
	new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
	new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
	new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
));

$cli->run();