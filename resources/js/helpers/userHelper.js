import config from './../config.js';

const userHelper =  {
    saveJWTToLocalStorage : jwt => {
        localStorage.setItem(config.app_name+'_token', jwt);
    },
    deleteJWTToLocalStorage : jwt => {
        localStorage.removeItem(config.app_name+'_token');
    },
    getJWTFromLocalStorage : () => {
        var token = localStorage.getItem(config.app_name+'_token');
        return token;
    },
    authenticationBearer : () => {
        var result = {
            headers: { Authorization: "bearer " }
        };
        var token = userHelper.getJWTFromLocalStorage();
        if(typeof token != 'undefined'){
            result = {
                headers: { Authorization: "bearer " + token }
            }
        }

        return result;
    },
    getUserRoles: () => {
        return userRoles;
    },
    getUserRoleByType : type => {
        let roles = userRoles.filter(role => role.value===type);
        if(roles.length > 0){
            return roles[0].text;
        }else{
            return '-';
        }
    }
}

export default userHelper;