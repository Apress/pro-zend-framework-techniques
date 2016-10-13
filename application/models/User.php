<?php
class Model_User extends Zend_Db_Table_Abstract
{
    /**
     * The default table name
     */
    protected $_name = 'users';
    public function createUser ($username, $password, $firstName, $lastName, $role)
    {
        // create a new row
        $rowUser = $this->createRow();
        if ($rowUser) {
            // update the row values
            $rowUser->username = $username;
            $rowUser->password = md5($password);
            $rowUser->first_name = $firstName;
            $rowUser->last_name = $lastName;
            $rowUser->role = $role;
            $rowUser->save();
            //return the new user
            return $rowUser;
        } else {
            throw new Zend_Exception("Could not create user! ");
        }
    }
    
public static function getUsers()
{
    $userModel = new self();
    $select = $userModel->select();
    $select->order(array('last_name', 'first_name'));
    return $userModel->fetchAll($select);
}

public function updateUser($id, $username, $firstName, $lastName, $role)
{
    // fetch the user's row
    $rowUser = $this->find($id)->current();

    if($rowUser) {
        // update the row values
        $rowUser->username = $username;
        $rowUser->first_name = $firstName;
        $rowUser->last_name = $lastName;
        $rowUser->role = $role;
        $rowUser->save();
        //return the updated user
        return $rowUser;
    }else{
        throw new Zend_Exception("User update failed.  User not found!");
    }
}
public function updatePassword($id, $password)
{
    // fetch the user's row
    $rowUser = $this->find($id)->current();

    if($rowUser) {
        //update the password
        $rowUser->password = md5($password);
        $rowUser->save();
    }else{
        throw new Zend_Exception("Password update failed.  User not found!");
    }
}
public function deleteUser($id)
{
    // fetch the user's row
    $rowUser = $this->find($id)->current();
    if($rowUser) {
        $rowUser->delete();
    }else{
        throw new Zend_Exception("Could not delete user.  User not found!");
    }
}

}
