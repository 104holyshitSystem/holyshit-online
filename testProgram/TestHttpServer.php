<?php
/**
 * A PHP socket server demo file.
 *
 * @author     Asika
 * @email      asika@asikart.com
 * @date       2013-10-12
 *
 * @copyright  Copyright (C) 2013 - Asika.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Daemon Application.
 */
class DaemonHttpApplication
{
    /**
     * Socket address.
     *
     * @var string
     */
    protected $domain;

    /**
     * Socket port.
     *
     * @var int
     */
    protected $port;

    /**
     * Max backlog
     *
     * @var int
     */
    protected $maxBacklog = 16;

    /**
     * Set domain.
     *
     * @param  string  $domain  Your http server domain.
     *
     * @return  DaemonHttpApplication  Return self to support chaining.
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * set port
     *
     * @param  int  $post  The port which socket listened.
     *
     * @return  DaemonHttpApplication  Return self to support chaining.
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Create a daemon process.
     *
     * @return  DaemonHttpApplication  Return self to support chaining.
     */
    public function execute()
    {
        // Create first child.
        if(pcntl_fork())
        {
            // I'm the parent
            // Protect against Zombie children
            pcntl_wait($status);
            exit;
        }

        // Make first child as session leader.
        posix_setsid();

        // Create second child.
        if(pcntl_fork())
        {
            // If pid not 0, means this process is parent, close it.
            exit;
        }

        // Create Http server
        $this->createHttpServer();

        return $this;
    }

    /**
     * Create a http service.
     *
     * @return  DaemonHttpApplication  Return self to support chaining.
     */
    protected function createHttpServer()
    {
        // Set response body
        $response   = <<<RES
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Hello World</title>
    </head>
    <body>
        Hello World~~~!!!
    </body>
</html>
RES;

        // Count text length
        $responseLength = strlen($response);

        // Create socket.
        if(!($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)))
        {
            echo "Create socket failed!\n";
            exit;
        }

        // Bind socket
        if(!(socket_bind($socket, $this->domain, $this->port)))
        {
            echo "Bind socket failed!\n";
            exit;
        }

        // Listen socket
        if(!(socket_listen($socket, $this->maxBacklog)))
        {
            echo "Listen to socket failed!\n";
            exit;
        }

        // Infinity loop for listening
        while(true)
        {
            $acceptSocket = socket_accept($socket);

            if(!$acceptSocket)
            {
                continue;
            }
            else
            {
                socket_write($acceptSocket, $response, $responseLength);

                socket_close($acceptSocket);
            }
        } // End while
    }
}


/**
 * @see http://localhost/~joel.zhong/phpinfo.php
 * @see http://www.php.net/manual/en/sockets.examples.php#example-4676
 *
 */
// Execute
// ---------------------------------------------

if(empty($_SERVER['argv'][1]))
{
    fwrite(STDERR, "Missing argument 1, please provide a domain address.\n");
    die;
}

$http = new DaemonHttpApplication();

$http->setDomain('127.0.0.1')
    ->setPort(9999)
    ->execute();