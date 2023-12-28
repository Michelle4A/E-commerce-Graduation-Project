<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Generar reporte de Reservas por periodo</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <label for="">Desde: &nbsp;</label>
                                <input type="date" v-model="fechaInicio">
                            </div>
                            <div class="col-4">
                                <label for="">Hasta: &nbsp;</label>
                                <input type="date" v-model="fechaFinal">
                            </div>
                            <div class="col-4">
                                <a :href="'/reportes/reservas/rango?fechaInicio='+fechaInicio+'&fechaFinal='+fechaFinal" target="_blank" class="btn btn-primary btn-sm">Generar</a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data()
        {
            return {
                fechaInicio: null,
                fechaFinal: null,
            }
        },
        mounted()
        {
         console.log('component mounted')
        }
    }
</script>
