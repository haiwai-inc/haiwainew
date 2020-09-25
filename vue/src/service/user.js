import API from "../api"

/**
 * Temp user class
 */
class User extends API{
    username = "username"

    printUsername(){
        console.log(this.username)
    }
}

export default User