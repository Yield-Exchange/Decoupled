<!-- Button trigger modal -->
{{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#switchOrganizationModal">
    Launch demo modal
</button> --}}

<!-- Modal -->
<div class="modal fade show " data-backdrop="static" id="switchOrganizationModal" tabindex="-1" role="dialog"
    aria-labelledby="switchOrganizationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5> --}}
                <button type="button" id="closeModal" class="close" data-dismiss="moda78l" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"
                            fill="none">
                            <g clip-path="url(#clip0_15322_237085)">
                                <rect width="30" height="30" rx="5" fill="#44E0AA" />
                                <path
                                    d="M20.892 8.30178C20.7995 8.20907 20.6896 8.13553 20.5686 8.08534C20.4477 8.03516 20.318 8.00933 20.187 8.00933C20.0561 8.00933 19.9264 8.03516 19.8054 8.08534C19.6844 8.13553 19.5745 8.20907 19.482 8.30178L14.592 13.1818L9.70202 8.29178C9.60944 8.19919 9.49953 8.12575 9.37856 8.07565C9.2576 8.02554 9.12795 7.99976 8.99702 7.99976C8.86609 7.99976 8.73644 8.02554 8.61548 8.07565C8.49451 8.12575 8.3846 8.19919 8.29202 8.29178C8.19944 8.38436 8.126 8.49427 8.07589 8.61523C8.02579 8.7362 8 8.86585 8 8.99678C8 9.12771 8.02579 9.25736 8.07589 9.37832C8.126 9.49928 8.19944 9.60919 8.29202 9.70178L13.182 14.5918L8.29202 19.4818C8.19944 19.5744 8.126 19.6843 8.07589 19.8052C8.02579 19.9262 8 20.0558 8 20.1868C8 20.3177 8.02579 20.4474 8.07589 20.5683C8.126 20.6893 8.19944 20.7992 8.29202 20.8918C8.3846 20.9844 8.49451 21.0578 8.61548 21.1079C8.73644 21.158 8.86609 21.1838 8.99702 21.1838C9.12795 21.1838 9.2576 21.158 9.37856 21.1079C9.49953 21.0578 9.60944 20.9844 9.70202 20.8918L14.592 16.0018L19.482 20.8918C19.5746 20.9844 19.6845 21.0578 19.8055 21.1079C19.9264 21.158 20.0561 21.1838 20.187 21.1838C20.318 21.1838 20.4476 21.158 20.5686 21.1079C20.6895 21.0578 20.7994 20.9844 20.892 20.8918C20.9846 20.7992 21.058 20.6893 21.1081 20.5683C21.1583 20.4474 21.184 20.3177 21.184 20.1868C21.184 20.0558 21.1583 19.9262 21.1081 19.8052C21.058 19.6843 20.9846 19.5744 20.892 19.4818L16.002 14.5918L20.892 9.70178C21.272 9.32178 21.272 8.68178 20.892 8.30178Z"
                                    fill="#EFF2FE" />
                            </g>
                            <defs>
                                <clipPath id="clip0_15322_237085">
                                    <rect width="30" height="30" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"
                            fill="none">
                            <g clip-path="url(#clip0_15322_237087)">
                                <path
                                    d="M2.38219 16.3761C4.01093 9.43252 9.43252 4.01093 16.3761 2.38219C21.3906 1.20594 26.6094 1.20594 31.6239 2.38219C38.5675 4.01093 43.9891 9.43252 45.6178 16.3761C46.7941 21.3906 46.7941 26.6094 45.6178 31.6239C43.9891 38.5675 38.5675 43.9891 31.6239 45.6178C26.6094 46.7941 21.3906 46.7941 16.3761 45.6178C9.43253 43.9891 4.01093 38.5675 2.38219 31.6239C1.20594 26.6094 1.20594 21.3906 2.38219 16.3761Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                                <path
                                    d="M32.5241 17.6905C32.5257 16.0524 31.9862 14.4597 30.9896 13.1597C29.9929 11.8596 28.5949 10.9251 27.0126 10.5013C25.4303 10.0774 23.7523 10.1879 22.2392 10.8157C20.7262 11.4435 19.4628 12.5533 18.6453 13.9728C18.3636 14.461 18.2874 15.0412 18.4334 15.5856C18.5794 16.13 18.9357 16.5941 19.4239 16.8758C19.9121 17.1575 20.4923 17.2338 21.0367 17.0878C21.5811 16.9417 22.0452 16.5854 22.3269 16.0972C22.6073 15.6129 23.01 15.2108 23.4947 14.9312C23.9794 14.6516 24.5291 14.5042 25.0887 14.5039C25.9338 14.5039 26.7443 14.8397 27.342 15.4373C27.9396 16.0349 28.2753 16.8454 28.2753 17.6905C28.2753 18.5357 27.9396 19.3462 27.342 19.9438C26.7443 20.5414 25.9338 20.8772 25.0887 20.8772H25.0823C24.9453 20.8909 24.81 20.9187 24.6787 20.96C24.536 20.9745 24.3951 21.003 24.258 21.045C24.1414 21.1087 24.0312 21.1834 23.9287 21.2681C23.8067 21.3346 23.6914 21.4129 23.5846 21.5017C23.4891 21.6159 23.4058 21.7398 23.336 21.8714C23.2584 21.967 23.1894 22.0694 23.13 22.1773C23.0837 22.3238 23.0531 22.4747 23.0386 22.6277C23.0021 22.7497 22.9772 22.8749 22.9643 23.0016V25.126L22.9685 25.1494V26.1924C22.9696 26.7551 23.194 27.2944 23.5922 27.6919C23.9905 28.0894 24.5302 28.3126 25.0929 28.3126H25.0993C25.3783 28.312 25.6544 28.2565 25.9119 28.1493C26.1695 28.042 26.4034 27.885 26.6002 27.6874C26.7971 27.4897 26.9531 27.2552 27.0594 26.9972C27.1656 26.7393 27.22 26.4629 27.2195 26.1839L27.2152 24.7776C28.7441 24.3234 30.086 23.3889 31.0422 22.1124C31.9984 20.8359 32.518 19.2854 32.5241 17.6905ZM23.5952 32.1153C23.2969 32.4112 23.093 32.7889 23.0093 33.2006C22.9257 33.6123 22.966 34.0396 23.1251 34.4285C23.2843 34.8173 23.5552 35.1502 23.9036 35.3851C24.252 35.62 24.6621 35.7463 25.0823 35.748C25.6454 35.7432 26.1852 35.5227 26.5906 35.132C26.9851 34.729 27.206 34.1875 27.206 33.6236C27.206 33.0597 26.9851 32.5183 26.5906 32.1153C26.184 31.7363 25.6488 31.5256 25.0929 31.5256C24.537 31.5256 24.0018 31.7363 23.5952 32.1153Z"
                                    fill="#5063F4" />
                            </g>
                            <defs>
                                <clipPath id="clip0_15322_237087">
                                    <rect width="48" height="47" fill="white" transform="translate(0 0.5)" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div
                        style="color: #2A2A2A;font-family: Montserrat;font-size: 28px;font-style: normal;font-weight: 700;line-height: 32px;text-transform: capitalize;">
                        Switch to a new Account?

                    </div>
                </div>
                <div class="mt-4 ml-4">
                    <p style="color: #2A2A2A;font-family: Montserrat;font-size: 20px;font-style: normal;font-weight: 400;line-height: 26px;"
                        id="switch-notification">
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeModal2" class="btn btn-secondary"
                    style="display: flex;padding: 8px 30px;justify-content: center;align-items: center;border-radius: 20px;color:#5063F4;border: 2px solid  #5063F4; background:#fff"
                    data-dismiss="moda78l">No</button>
                <button type="button" id="SwitchNow"
                    style="display: flex;padding: 10px 30px;justify-content: center;align-items: center;border-radius: 20px;background:  #5063F4;"
                    class="btn btn-primary">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" data-backdrop="static" id="erroswitchOrganizationModal" tabindex="-1" role="dialog"
    aria-labelledby="erroswitchOrganizationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5> --}}
                <button type="button" id="CloseError" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"
                            fill="none">
                            <g clip-path="url(#clip0_15322_237085)">
                                <rect width="30" height="30" rx="5" fill="#44E0AA" />
                                <path
                                    d="M20.892 8.30178C20.7995 8.20907 20.6896 8.13553 20.5686 8.08534C20.4477 8.03516 20.318 8.00933 20.187 8.00933C20.0561 8.00933 19.9264 8.03516 19.8054 8.08534C19.6844 8.13553 19.5745 8.20907 19.482 8.30178L14.592 13.1818L9.70202 8.29178C9.60944 8.19919 9.49953 8.12575 9.37856 8.07565C9.2576 8.02554 9.12795 7.99976 8.99702 7.99976C8.86609 7.99976 8.73644 8.02554 8.61548 8.07565C8.49451 8.12575 8.3846 8.19919 8.29202 8.29178C8.19944 8.38436 8.126 8.49427 8.07589 8.61523C8.02579 8.7362 8 8.86585 8 8.99678C8 9.12771 8.02579 9.25736 8.07589 9.37832C8.126 9.49928 8.19944 9.60919 8.29202 9.70178L13.182 14.5918L8.29202 19.4818C8.19944 19.5744 8.126 19.6843 8.07589 19.8052C8.02579 19.9262 8 20.0558 8 20.1868C8 20.3177 8.02579 20.4474 8.07589 20.5683C8.126 20.6893 8.19944 20.7992 8.29202 20.8918C8.3846 20.9844 8.49451 21.0578 8.61548 21.1079C8.73644 21.158 8.86609 21.1838 8.99702 21.1838C9.12795 21.1838 9.2576 21.158 9.37856 21.1079C9.49953 21.0578 9.60944 20.9844 9.70202 20.8918L14.592 16.0018L19.482 20.8918C19.5746 20.9844 19.6845 21.0578 19.8055 21.1079C19.9264 21.158 20.0561 21.1838 20.187 21.1838C20.318 21.1838 20.4476 21.158 20.5686 21.1079C20.6895 21.0578 20.7994 20.9844 20.892 20.8918C20.9846 20.7992 21.058 20.6893 21.1081 20.5683C21.1583 20.4474 21.184 20.3177 21.184 20.1868C21.184 20.0558 21.1583 19.9262 21.1081 19.8052C21.058 19.6843 20.9846 19.5744 20.892 19.4818L16.002 14.5918L20.892 9.70178C21.272 9.32178 21.272 8.68178 20.892 8.30178Z"
                                    fill="#EFF2FE" />
                            </g>
                            <defs>
                                <clipPath id="clip0_15322_237085">
                                    <rect width="30" height="30" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="61" viewBox="0 0 60 61"
                            fill="none">
                            <path
                                d="M8.88219 23.3761C10.5109 16.4325 15.9325 11.0109 22.8761 9.38219C27.8906 8.20594 33.1094 8.20594 38.1239 9.38219C45.0675 11.0109 50.4891 16.4325 52.1178 23.3761C53.2941 28.3906 53.2941 33.6094 52.1178 38.6239C50.4891 45.5675 45.0675 50.9891 38.1239 52.6178C33.1094 53.7941 27.8906 53.7941 22.8761 52.6178C15.9325 50.9891 10.5109 45.5675 8.88219 38.6239C7.70594 33.6094 7.70594 28.3906 8.88219 23.3761Z"
                                fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                            <path
                                d="M30.0015 27.0254H33V33.9004H30.0015V27.0254ZM30 35.2754H32.9985V38.0254H30V35.2754Z"
                                fill="#5063F4" />
                            <path
                                d="M33.4602 18.5118C32.9758 17.595 32.0323 17.0254 30.9996 17.0254C29.967 17.0254 29.0234 17.595 28.5391 18.5132L18.3267 37.9161C18.1001 38.3422 17.9879 38.8203 18.001 39.3033C18.0142 39.7864 18.1524 40.2576 18.4019 40.6705C18.6478 41.0854 18.997 41.4285 19.4151 41.6661C19.8332 41.9037 20.3057 42.0275 20.7859 42.0254H41.2134C42.1987 42.0254 43.0908 41.5187 43.5987 40.6705C43.8479 40.2574 43.9858 39.7863 43.999 39.3033C44.0121 38.8204 43.9001 38.3423 43.6739 37.9161L33.4602 18.5118ZM20.7859 39.2261L30.9996 19.8232L41.2203 39.2261H20.7859Z"
                                fill="#5063F4" />
                        </svg>
                    </div>
                    <div
                        style="color: #2A2A2A;font-family: Montserrat;font-size: 28px;font-style: normal;font-weight: 700;line-height: 32px;text-transform: capitalize;">
                        Sorry,you cannot switch into this organization
                    </div>
                </div>
                <div class="mt-4 ml-4">
                    <p style="color: #2A2A2A;font-family: Montserrat;font-size: 20px;font-style: normal;font-weight: 400;line-height: 26px;"
                        id="switch-notification-error">
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" id="closeModal" class="btn btn-secondary"
                    style="display: flex;padding: 8px 30px;justify-content: center;align-items: center;border-radius: 20px;color:#5063F4;border: 2px solid  #5063F4; background:#fff"
                    data-dismiss="modal">No</button> --}}
                <button type="button" id="CloseError2" data-dismiss="modal"
                    style="display: flex;padding: 10px 30px;justify-content: center;align-items: center;border-radius: 20px;background:  #5063F4;"
                    class="btn btn-primary">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#switch-select').on('change', () => {
        const selectElement = document.getElementById('switch-select');
        const selectedValue = selectElement.value;
        // const selectedText = selectElement.text();
        var selectedText = $('#switch-select option:selected').text();

        // console.log('Selected value:', selectedText);
        // console.log("select change")
        var selectOptions = {
            show: true
        }
        $('#switchOrganizationModal').modal('show')
        $('#switch-notification').html(`Would you like to switch to<b> ${selectedText}</b>`)
    })

    $('#closeModal,#closeModal2').on('click', () => {
        const selectElement = document.getElementById('switch-select');
        selectElement.value = user_object.organization_id;
        // console.log("Clicked", user_object)
        $('#switchOrganizationModal').modal('hide')


    })
    $('#CloseError,#CloseError2').on('click', () => {
        const selectElement = document.getElementById('switch-select');
        selectElement.value = user_object.organization_id;
        $('#erroswitchOrganizationModal').modal('hide')


    })
    $('#SwitchNow').on('click', () => {
        const selectElement = document.getElementById('switch-select');
        const selectedValue = selectElement.value;
        $('#switchOrganizationModal').modal('hide')

        axios.post('/switch-organization', {
            'organization_id': selectedValue
        }).then(response => {
            if (response.data.success) {
                window.location.href = '/launchpad'
            }
            // console.log(response)
        }).catch(err => {
            erroswitchOrganizationModal
            $('#switch-notification-error').html(`${err.response.data.message}`)
            $('#erroswitchOrganizationModal').modal('show')
            selectElement.value = user_object.organization_id;


            // console.log(err.response.data.message)

        })

    })
</script>
