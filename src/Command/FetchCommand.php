<?php

    namespace App\Command;

    use App\Entity\Posts;
    use Symfony\Component\Console\Attribute\AsCommand;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\HttpClient\HttpClient;
    use Doctrine\ORM\EntityManagerInterface;


    #[AsCommand(
        name: 'app:fetch-data',
        description: 'fetch data from fake api',
        hidden:false,
        aliases: ['app:get-data']
    )]

    class FetchCommand extends Command{

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

            $rep=$em->getRepository('\App\Entity\Posts');

            $httpClient=HttpClient::create();      
            $response=$httpClient->request('GET','https://jsonplaceholder.typicode.com/posts/');
            $content=json_decode($response->getContent(),true);

            $response=$httpClient->request('GET','https://jsonplaceholder.typicode.com/users/');
            $UsersContent=json_decode($response->getContent(),true);

            foreach($content as $array){
                $postt=new Posts();
                $authorId=($array['userId']-1);
                // var_dump($authorId);
                $postt->setTitle($array['title']);
                $postt->setUserId($array['userId']);
                $postt->setBody($array['body']);
                $postt->setAuthor($UsersContent[$authorId]['name']);

                $em->persist($postt);
                $em->flush();
            }
            

            
            
           
            $output->write('gotowe');
            return Command::SUCCESS;


            
        }


    }


?>