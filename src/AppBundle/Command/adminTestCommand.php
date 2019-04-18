<?php

namespace AppBundle\Command;

//use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Contactus;


class adminTestCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('app:generar-inserts')

                // the short description shown while running "php bin/console list"
                ->setDescription('Crea el mapeo de localidades de el salvador')

                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp("Crea los deparatamentos y municipios de el salvador")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) 
	{
        
		$em = $this->getContainer()->get('doctrine')->getManager();
		//$em = $this->getDoctrine()->getManager();
		
		//======================================================================
		//Municipios
		//======================================================================
		
		
		$oCountry = new Contactus();
		$oCountry->setConName( "Gio" );
		$oCountry->setConEmail( "gialvarez@hotik.com" );
		$oCountry->setConPhone( "123456" );
		$oCountry->setConComment( "COMENTARIO" );
		$oCountry->setConCreate( new \DateTime() ); 
		$em->persist($oCountry);			
		$flush = $em->flush();
		if ($flush == null)
		{
			$output->writeln([
				'',
				'=========================================================',
				'Ejemplo Insertado',
				'=========================================================',
				'',
				//'Medicos a crear: ' . $num,
				//'ID:'.$oCountry->getPaiId(),
			]);
			
			
		}
		else
		{
			$output->writeln('Erroren el demo');
		}

    }

}
