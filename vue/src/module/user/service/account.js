import API from '../../../service/api'

class Account extends API{

    signal(message){
        console.log(message)
    }

}

export default Account