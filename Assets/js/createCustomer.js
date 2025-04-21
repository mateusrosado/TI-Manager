
var interval;
var xhr;
$('#franchiseeCnpj').on("keyup", function () {
    let cnpj = $(this).val()
    cnpj = cnpj.replace(/[^0-9]/g, '');
    
    // if (cnpj.length < 14) {
    //     warnings('error', 'Digite todos os números do cnpj')
    // }

    if (cnpj.length == 14) {
        if (xhr) {
            xhr.abort();
        }
    
        xhr = $.ajax({
            url: `https://publica.cnpj.ws/cnpj/${cnpj.replace(/[^0-9]/g, '')}`,
            type: 'GET',
            success: function (json) {
    
                $('#franchiseeCorporateName').val(json.razao_social).attr('disabled', 'disabled');
                $('#franchiseeCorporateNameHidden').val(json.razao_social);
                $('#franchiseeFantasyName').val(json.estabelecimento.nome_fantasia).attr('disabled', 'disabled');
                $('#franchiseeFantasyNameHidden').val(json.estabelecimento.nome_fantasia);

            },
            error: function (error) {

                console.log(error);

                if (error.responseJSON != undefined && error.responseJSON.status == 400) {
                    warnings('CNPJError', 5000)
                    $('#franchiseeCnpj').addClass('is-invalid')
                }

                if (error.responseJSON != undefined && error.responseJSON.status == 404) {
                    warnings('CNPJNotFound')
                    $('#franchiseeCnpj').addClass('is-invalid')
                }

                if (error.responseJSON != undefined && error.responseJSON.status == 429) {
                    // Error 429
                    warnings('warning', 'Recarregue a página e espere alguns minutos para tentar novamente.')
                }

                if (error.status == 0) {
                    $('#franchiseeCorporateName').removeAttr('disabled', 'disabled')
                }

                // warnings('error', 'CNPJ inválido ou inexistente, certifique-se que digitou o cnpj corretamente e tente novamente!');
            }
        })
    
        setTimeout(function () {
            if (xhr && xhr.readyState !== 4) {
                xhr.abort();
            }
        }, 10000);
    }

})

var interval;
var xhr;
$('#simpleCnpj').on("keyup", function () {
    let cnpj = $(this).val()
    cnpj = cnpj.replace(/[^0-9]/g, '');
    
    // if (cnpj.length < 14) {
    //     warnings('error', 'Digite todos os números do cnpj')
    // }

    if (cnpj.length == 14) {
        if (xhr) {
            xhr.abort();
        }
    
        xhr = $.ajax({
            url: `https://publica.cnpj.ws/cnpj/${cnpj.replace(/[^0-9]/g, '')}`,
            type: 'GET',
            success: function (json) {
    
                $('#simpleCorporateName').val(json.razao_social).attr('disabled', 'disabled');
                $('#simpleCorporateNameHidden').val(json.razao_social);
                $('#simpleFantasyName').val(json.estabelecimento.nome_fantasia).attr('disabled', 'disabled');
                $('#simpleFantasyNameHidden').val(json.estabelecimento.nome_fantasia);

            },
            error: function (error) {

                console.log(error);

                if (error.responseJSON != undefined && error.responseJSON.status == 400) {
                    warnings('CNPJError', 5000)
                    $('#simpleCnpj').addClass('is-invalid')
                }

                if (error.responseJSON != undefined && error.responseJSON.status == 404) {
                    warnings('CNPJNotFound')
                    $('#simpleCnpj').addClass('is-invalid')
                }

                if (error.responseJSON != undefined && error.responseJSON.status == 429) {
                    // Error 429
                    warnings('warning', 'Recarregue a página e espere alguns minutos para tentar novamente.')
                }

                if (error.status == 0) {
                    $('#simpleCorporateName').removeAttr('disabled', 'disabled')
                }

                // warnings('error', 'CNPJ inválido ou inexistente, certifique-se que digitou o cnpj corretamente e tente novamente!');
            }
        })
    
        setTimeout(function () {
            if (xhr && xhr.readyState !== 4) {
                xhr.abort();
            }
        }, 10000);
    }

})

var interval;
function cepSearch(obj) {
    cep = $(obj).val();
    complement = $(obj).data('complement')

    clearTimeout(interval);
    cep = cep.replace(/[^0-9]/g, '');

    interval = setTimeout(() => {
        clearTimeout(interval);
        if (cep.length == 8) {
            $.ajax({
                url: `https://viacep.com.br/ws/${cep}/json/`,
                method: 'GET',
                dataType: 'json'
            }).done(function (json) {
                if (json.erro) {
                    warnings('error', 'O CEP buscado não foi encontrado!')

                    $(`#${complement}State`).val('').removeAttr('disabled');
                    $(`#${complement}StateHidden`).val('');
                    $(`#${complement}City`).val('').removeAttr('disabled');
                    $(`#${complement}City`).empty('').append('<option value="">Selecione</option>');
                    $(`#${complement}CityHidden`).val('');
                    $(`#${complement}District`).val('');
                    $(`#${complement}Street`).val('');
                } else {
                    searchCities(json.uf, json.localidade, complement)

                    $(`#${complement}State`).val(json.uf).attr('disabled', 'disabled');
                    $(`#${complement}StateHidden`).val(json.uf);
                    $(`#${complement}City`).val(json.localidade);
                    $(`#${complement}CityHidden`).val(json.localidade);
                    $(`#${complement}District`).val(json.bairro);
                    $(`#${complement}Street`).val(json.logradouro);
                }
            })
        }
    }, 500);
}

function searchCities(obj, city, complementId) {
    var value = '';

    $(obj).val() != undefined ? value = $(obj).val() : value = obj

    let html = '<option value="">Selecione</option>';
    $.ajax({
        url: `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${value}/municipios`,
        method: 'GET',
        dataType: 'json'
    }).done(function (json) {
        if (!json.erro) {
            $(`#${complementId}City`).empty();
            json.forEach(item => {
                html += `<option ${city != undefined && city == item.nome ? 'selected' : ''} value="${item.nome}">${item.nome}</option>`;
                $(`#${complementId}City`).append(html);
            })

            if (city != undefined) {
                $(`#${complementId}City`).attr('disabled', 'disabled');
            }
        }
    })
}

// ------------------------------------------------

$('#brandSelect').on('change', function () {
    let value = $(this).val();

    if (value.trim() != '' && value == 'other') {
        $('#areaInput').show('fast');
        $('#franchiseeBrand').attr('required', 'required');
    } else if (value.trim() != '' && value != 'other') {
        $('#areaInput').hide('fast');
        $('#franchiseeBrand').removeAttr('required', 'required');
        $('#franchiseeBrand').val('');
    }
});