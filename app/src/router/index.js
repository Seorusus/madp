import Vue from 'vue'
import Router from 'vue-router'
import QuotationStep1 from '@/pages/tunnel/QuotationStep1'
import QuotationStep2 from '@/pages/tunnel/QuotationStep2'
import Subscription from '@/pages/tunnel/Subscription'
import Signature from '@/pages/tunnel/Signature'
import Connection from '@/pages/account/Connection'
import DefaultHome from '@/pages/account/DefaultHome'
import Client from '@/pages/account/Client'
import ClientDashboard from '@/pages/account/ClientDashboard'
import ContractsList from '@/pages/account/ContractsList'
import WorkLossContractDetails from '@/pages/account/WorkLossContractDetails'
import WorkLossContractDetailsEdit from '@/pages/account/WorkLossContractDetailsEdit'
import MyInfo from '@/pages/account/MyInfo'
import MyLosses from '@/pages/account/MyLosses'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/devis-1',
            name: 'QuotationStep1',
            component: QuotationStep1
        },
        {
            path: '/devis-2',
            name: 'QuotationStep2',
            component: QuotationStep2
        },
        {
            path: '/souscription',
            name: 'Subscription',
            component: Subscription
        },
        {
            path: '/signature',
            name: 'Signature',
            component: Signature
        },
        {
            path: "/espace-client",
            name: "Client",
            component: Client,
            meta: {
                bcLinkText: 'Mon espace'
            },
            children: [
                {
                    path: '/connexion',
                    name: 'Connection',
                    component: Connection
                },
                {
                    path: 'mes-contrats',
                    name: "ContractHome",
                    component: DefaultHome,
                    meta: {
                        bcLinkText: 'Mes Contrats'
                    },
                    children: [
                        {
                            path: 'assurance-chomage/:id',
                            name: 'AssuranceChomageHome',
                            component: DefaultHome,
                            meta: {
                                bcLinkText: 'Mon assurance chomage'
                            },
                            children:[
                                {
                                    path: '',
                                    name: 'WorkLossContractDetails',
                                    component: WorkLossContractDetails
                                },
                                {
                                    path: 'edition',
                                    name: 'WorkLossContractDetailsEdit',
                                    component: WorkLossContractDetailsEdit,
                                    meta: {
                                        bcLinkText: 'Modifier les informations du contrat'
                                    },
                                }
                            ]
                        },
                        {
                            path: '',
                            name: "ContractsList",
                            component: ContractsList
                        }
                    ]
                },
                {
                    path: 'mes-infos',
                    name: 'MyInfo',
                    component: MyInfo,
                        meta: {
                        bcLinkText: 'Mes Informations'
                    }
                },
                {
                    path: 'mes-sinistres',
                    name: 'MyLosses',
                    component: MyLosses,
                    meta: {
                        bcLinkText: 'Mes Sinistres'
                    }
                },
                {
                    path: '',
                    name: 'ClientDashboard',
                    component: ClientDashboard
                }
            ]
        },
        {
            path: '*', redirect: {name: 'QuotationStep1'}
        }
    ]
})
