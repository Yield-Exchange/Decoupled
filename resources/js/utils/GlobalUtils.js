export const confirmLeavePage = (the_this, document, custom_message=null, cancelButtonText='No',confirmButtonText='Yes') => {
    document.querySelectorAll("a").forEach(ref__ =>
        ref__.addEventListener("click", (e) => {
            e.preventDefault();

            if (e.target.classList.contains('no-page-exit-alert')) {
                window.location.href = e.target.getAttribute('href');
                return;
            } else if (e.target.closest(".navbar-nav-link") && e.target.closest(".navbar-nav-link").classList.contains('no-page-exit-alert')) {
                return;
            }

            the_this.$swal({
                title: "Do you want to leave this page?",
                text: custom_message ? custom_message  :"Changes you made will not be saved.",
                showCancelButton: true,
                cancelButtonText: cancelButtonText,
                confirmButtonText: confirmButtonText,
                confirmButtonColor: '#4975E3',
                cancelButtonColor: '#E9ECEF',
                customClass: {
                    actions: 'swal-button-actions',
                    confirmButton: 'custom-primary round',
                    cancelButton: 'custom-secondary round'
                }
            }).then((response) => {
                if (response.isConfirmed) {
                    if (e.target.getAttribute('href')) {
                        window.location.href = e.target.getAttribute('href');
                    } else {
                        let a_ = e.target.closest(".nav-link");
                        window.location.href = a_.getAttribute('href');
                    }
                }
            });
        }));
};

export const currencyFormatter = (amount) => {
    return amount;
    // return new Intl.NumberFormat('en-US', {
    //     style: 'currency',
    //     currency: currency,
    //
    //     // These options are needed to round to whole numbers if that's what you want.
    //     //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
    //     //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    // });
};

export const amountFormatter = (amount) => {
    return amount;
};

export const userCan = (user, permission) => {
    if (user.is_super_admin) {
        //Further checks can be done for specific admin permissions
        return true;
    }

    if (user.role_name === "Organization Administrator") {
        return true;
    }

    if (user.assignRoles.some((item) => {
        return item.permission_name === permission;
    })) {
        return true;
    }

    return false;
};

export const getIPAddress = async () => {
    const accessToken = '21f42bdc4db951';
    try {
        const response = await fetch('https://api.ipify.org/?format=json&token=' + accessToken);
        const data = await response.json();
  
        let cachedIpAddress = data?.ip;
        localStorage.setItem("ip_address",data?.ip);
        
        await axios.post('/set-session', { 'my_ip': cachedIpAddress });
        // console.log(data?.ip);

        return data?.ip

    } catch (error) {
        // console.error('Error:', error);
        // return Promise.reject(error);
    }
}
