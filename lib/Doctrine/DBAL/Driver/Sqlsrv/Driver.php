<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\DBAL\Driver\Sqlsrv;

use Doctrine\DBAL\Platforms;

/**
 * A Doctrine DBAL driver for the Microsoft SQL Native Client PHP extension.
 * 
 * @since 2.0
 * @author Juozas Kaziukenas <juozas@juokaz.com>
 */
class Driver implements \Doctrine\DBAL\Driver
{
    public function connect(array $params, $username = null, $password = null, array $driverOptions = array())
    {
        $serverName = '';
        // construct server name
        $serverName = $params['host'];
        if (isset($params['port']) && !empty($params['port'])) {
            $port        = (integer) $params['port'];
            $serverName .= ', ' . $port;
        }
        
        $connectionInfo = array(
            'Database' => $params['dbname'],
        );

        if (isset($username) && !empty($username) && isset($password) && !empty($password))
        {
            $connectionInfo += array(
                'UID'      => $username,
                'PWD'      => $password,
            );
        }

        $connectionInfo += array('ReturnDatesAsStrings' => true);
        
        return new SqlsrvConnection($serverName, $connectionInfo);
    }

    public function getDatabasePlatform()
    {
        return new \Doctrine\DBAL\Platforms\SqlsrvPlatform();
    }

    public function getSchemaManager(\Doctrine\DBAL\Connection $conn)
    {
        return new \Doctrine\DBAL\Schema\SqlsrvSchemaManager($conn);
    }

    public function getName()
    {
        return 'sqlsrv';
    }

    public function getDatabase(\Doctrine\DBAL\Connection $conn)
    {
        $params = $conn->getParams();
        return $params['dbname'];
    }
}