<?php

    namespace App\Command;

    use App\Entity\Authors;
    use Symfony\Component\Console\Attribute\AsCommand;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\HttpClient\HttpClient;
    use Doctrine\ORM\EntityManagerInterface;



    #[AsCommand(
        name: 'app:fetch-users',
        description: 'fetch data from fake api',
        hidden:false,
        aliases: ['app:get-users']
    )]

    class GetUsersCommand extends Command{

        //protected static $defaultName='app:fetch-data';

        private $entityManager;
        private $client;
        private $content;

        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager=$entityManager;
            parent::__construct();
        }


        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            $em=$this->entityManager;

            $rep=$em->getRepository('\App\Entity\Authors');

            $httpClient=HttpClient::create();
            $response=$httpClient->request('GET','https://jsonplaceholder.typicode.com/users/');
            $content=json_decode($response->getContent(),true);

            //var_dump($content);

            foreach($content as $array){
                $author=new Authors();
                $author->setName($array['name']);

                $em->persist($author);
                $em->flush();
            }
            

            
            
           
            $output->write('ugabuga');
            return Command::SUCCESS;


            
        }


    }


?>