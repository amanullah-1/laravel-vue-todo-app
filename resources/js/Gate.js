export default class Gate{

    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.role === 'admin';
    }

    isUser(){
        return this.user.role === 'user';
    }
    
    isAdminOrUser(){
        if(this.user.role === 'user' || this.user.role === 'admin'){
            return true;
        }
    }

    userId(){
        return this.user.id;
    }
}

