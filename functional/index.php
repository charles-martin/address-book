<?php
    /**
     * Created by PhpStorm.
     * User: Charles.Martin
     * Date: 8/11/2015
     * Time: 10:21 AM
     */

    function getDBConnection() {
        static $dbConn;
        if (!$dbConn) {
            $dbConn = new PDO('mysql:host=localhost;dbname=dbname', 'root', '123');
            $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $dbConn;
    }

    function deleteAddress($id) {
        $sQuery = 'DELETE FROM address WHERE id = ' . (int) $id;
        getDBConnection()->exec($sQuery);
    }

    function insertAddress($data) {
        $sQuery = 'INSERT into address (name, street, city, state, zip, phone, cell, fax, email) VALUES (:name, :street, :city, :state, :zip, :phone, :cell, :fax, :email)';
        $pdStatement = getDBConnection()->prepare($sQuery);
        foreach($data as $key => $value) {
            $pdStatement->bindValue(':' . $key, $value);
        }

        $pdStatement->execute();
    }

    function saveAddress($id, $data) {
        $sQuery = 'UPDATE address SET name = :name, street = :street, city = :city, state = :state, zip = :zip, phone = :phone, cell = :cell, fax = :fax, email = :email WHERE id = ' . (int) $id;

        $pdStatement = getDBConnection()->prepare($sQuery);

        foreach($data as $key => $value) {
            $pdStatement->bindValue(':' . $key, $value);
        }

        $pdStatement->execute();
    }

    if (isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'add':
                $aInsert = array_intersect_key($_POST, ['name' => '', 'street' => '', 'city' => '', 'state' => '', 'zip' => '', 'phone' => '', 'cell' => '', 'fax' => '', 'email' => '']);
                insertAddress($aInsert);
                header('Location: /');
                exit;
            case 'edit':
                $aInsert = array_intersect_key($_POST, ['name' => '', 'street' => '', 'city' => '', 'state' => '', 'zip' => '', 'phone' => '', 'cell' => '', 'fax' => '', 'email' => '']);
                saveAddress($_POST['id'], $aInsert);
                header('Location: /');
                exit;
        }
    } else if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        deleteAddress($_GET['id']);
        header('Location: /');
        exit;
    }
?><html>
    <head>
        <title>Address Book</title>
    </head>
    <body>
        <?php if (isset($_GET['action']) && $_GET['action'] == 'edit'): ?>
            <?php
                $query   = 'SELECT * FROM address WHERE id = ' . (int) $_GET['id'];
                $pdStatement = getDBConnection()->prepare($query);
                $pdStatement->execute();
                $address = $pdStatement->fetch();
            ?>
            <form action="/" method="post">
                <table>
                    <tr>
                        <th>Name</th>
                        <td><input type="text" name="name" value="<?php echo $address['name']; ?>" /></td>
                    </tr>
                    <tr><td colspan="2">Address</td></tr>
                    <tr>
                        <th>Street</th>
                        <td><input type="text" name="street" value="<?php echo $address['street']; ?>" /></td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td><input type="text" name="city" value="<?php echo $address['city']; ?>" /></td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td><input type="text" name="state" value="<?php echo $address['state']; ?>" /></td>
                    </tr>
                    <tr>
                        <th>Zip</th>
                        <td><input type="text" name="zip" value="<?php echo $address['zip']; ?>" /></td>
                    </tr>
                    <tr><td colspan="2">Contact</td></tr>
                    <tr>
                        <th>Phone #</th>
                        <td><input type="text" name="phone" value="<?php echo $address['phone']; ?>" /></td>
                    </tr>
                    <tr>
                        <th>Cell #</th>
                        <td><input type="text" name="cell" value="<?php echo $address['cell']; ?>" /></td>
                    </tr>
                    <tr>
                        <th>Fax #</th>
                        <td><input type="text" name="fax" value="<?php echo $address['fax']; ?>" /></td>
                    </tr>
                    <tr>
                        <th>E-Mail</th>
                        <td><input type="text" name="email" value="<?php echo $address['email']; ?>" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="action" value="edit" />
                            <input type="hidden" name="id" value="<?php echo $address['id']; ?>" />
                            <input type="submit" value="Save" />
                        </td>
                    </tr>
                </table>
            </form>
        <?php elseif (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
            <form action="/" method="post">
                <table>
                    <tr>
                        <th>Name</th>
                        <td><input type="text" name="name" value="" /></td>
                    </tr>
                    <tr><td colspan="2">Address</td></tr>
                    <tr>
                        <th>Street</th>
                        <td><input type="text" name="street" value="" /></td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td><input type="text" name="city" value="" /></td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td><input type="text" name="state" value="" /></td>
                    </tr>
                    <tr>
                        <th>Zip</th>
                        <td><input type="text" name="zip" value="" /></td>
                    </tr>
                    <tr><td colspan="2">Contact</td></tr>
                    <tr>
                        <th>Phone #</th>
                        <td><input type="text" name="phone" value="" /></td>
                    </tr>
                    <tr>
                        <th>Cell #</th>
                        <td><input type="text" name="cell" value="" /></td>
                    </tr>
                    <tr>
                        <th>Fax #</th>
                        <td><input type="text" name="fax" value="" /></td>
                    </tr>
                    <tr>
                        <th>E-Mail</th>
                        <td><input type="text" name="email" value="" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="action" value="add" />
                            <input type="submit" value="Save" />
                        </td>
                    </tr>
                </table>
            </form>
        <?php elseif (isset($_GET['action']) && $_GET['action'] == 'details'): ?>
            <?php
                $query   = 'SELECT * FROM address WHERE id = ' . (int) $_GET['id'];
                $pdStatement = getDBConnection()->prepare($query);
                $pdStatement->execute();
                $address = $pdStatement->fetch();
            ?>
            <table>
                <tr>
                    <th>Name</th>
                    <td><?php echo $address['name']; ?></td>
                </tr>
                <tr><td colspan="2">Address</td></tr>
                <tr>
                    <th>Street</th>
                    <td><?php echo $address['street']; ?></td>
                </tr>
                <tr>
                    <th>City</th>
                    <td><?php echo $address['city']; ?></td>
                </tr>
                <tr>
                    <th>State</th>
                    <td><?php echo $address['state']; ?></td>
                </tr>
                <tr>
                    <th>Zip</th>
                    <td><?php echo $address['zip']; ?></td>
                </tr>
                <tr><td colspan="2">Contact</td></tr>
                <tr>
                    <th>Phone #</th>
                    <td><?php echo $address['phone']; ?></td>
                </tr>
                <tr>
                    <th>Cell #</th>
                    <td><?php echo $address['cell']; ?></td>
                </tr>
                <tr>
                    <th>Fax #</th>
                    <td><?php echo $address['fax']; ?></td>
                </tr>
                <tr>
                    <th>E-Mail</th>
                    <td><?php echo $address['email']; ?></td>
                </tr>
            </table>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th><a href="/?order=name&search=<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>">Name</a></th>
                        <th><a href="/?order=email&search=<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>">E-Mail</a></th>
                        <th><a href="/?order=city&search=<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>">City</a></th>
                        <th><a href="/?order=state&search=<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>">State</a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">
                            <form method="get" action="/?page=1">
                                <div>
                                    <input style="width: 75%;" type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>" />
                                    <input type="submit" value="Search" />
                                </div>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <a href="/?action=add">Add New</a>
                        </td>
                    </tr>
                <?php
                    $query   = 'SELECT COUNT(id) as cnt FROM address';
                    if (isset($_GET['search']) && $_GET['search'] != '') {
                        $query .= ' WHERE name LIKE :search ';
                    }

                    $pdStatement = getDBConnection()->prepare($query);
                    if (isset($_GET['search']) && $_GET['search'] != '') {
                        $pdStatement->bindValue(':search', '%' . $_GET['search'] . '%');
                    }

                    $pdStatement->execute();
                    $total = (int) $pdStatement->fetchColumn(0);

                    $query   = 'SELECT * FROM address';
                    if (isset($_GET['search']) && $_GET['search'] != '') {
                        $query .= ' WHERE name LIKE :search ';
                    }

                    $order = isset($_GET['order']) ? $_GET['order'] : 'name';
                    $query .= ' ORDER BY ' . $order . ' ASC ';

                    $page = 1;
                    if (isset($_GET['page'])) {
                        $page = (int) $_GET['page'];
                    }

                    $query .= ' LIMIT ' . (($page - 1) * 5) . ', 5';

                    $pdStatement = getDBConnection()->prepare($query);
                    if (isset($_GET['search']) && $_GET['search'] != '') {
                        $pdStatement->bindValue(':search', '%' . $_GET['search'] . '%');
                    }

                    $pdStatement->execute();

                    $results = $pdStatement->fetchAll();
                ?>
                <?php foreach($results as $row): ?>
                    <tr>
                        <td><a href="/?action=details&id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
                        <td><a href="mailto:<?php echo $row['email'] ?>"><?php echo $row['email']; ?></a></td>
                        <td><?php echo $row['city']; ?></td>
                        <td><?php echo $row['state']; ?></td>
                        <td>
                            <ul class="actions">
                                <li><a href="/?action=edit&id=<?php echo $row['id']; ?>">Edit</a></li>
                                <li><a href="/?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a></li>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach; ?>
                    <tr>
                        <td colspan="4">
                            <ul>
                                <?php for($i = 1; $i <= ceil($total / 5); $i++): ?>
                                    <?php if ($page === $i): ?>
                                        <li>[<?php echo $i; ?>]</li>
                                    <?php else: ?>
                                        <li><a href="/?order=<?php echo isset($_GET['order']) ? $_GET['order'] : ''; ?>&page=<?php echo $i; ?>&search=<?php echo isset($_GET['search']) ? $_GET['search'] : '';?>">[<?php echo $i; ?>]</a></li>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </body>
</html>