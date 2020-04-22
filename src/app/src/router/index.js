import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

  const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/historic',
    name: 'Historic',
    component: () => import(/* webpackChunkName: "about" */ '../views/historic.vue')
  },
  {
    path: '/edit/:id/:temp/:hum',
    name: 'Edit',
    component: ()  => import('../views/Edit')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
