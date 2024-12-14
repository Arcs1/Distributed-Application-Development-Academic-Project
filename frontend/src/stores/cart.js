import {inject, ref} from 'vue'
import {defineStore} from 'pinia'
import {useStorage} from '@vueuse/core'

export const useCartStore = defineStore('cart', () =>
{
    const toast = inject('toast')
    const itemCount = useStorage('itemCount',0)
    const items = useStorage('items',[])
    const total = useStorage('total',0.0)
    function addItem(item){
        items.value.push(item)
        total.value = (parseFloat(total.value) + parseFloat(item.price)).toFixed(2)
        itemCount.value++
        toast.success("Item Added with Success.", {
  
  position: "bottom-right",
});
    }

    function removeItem(item){
        const index = items.value.indexOf(item)
        items.value.splice(index,1)
        total.value = (parseFloat(total.value) - parseFloat(item.price)).toFixed(2)
        itemCount.value--
        toast.error("Item Removed with Success." ,{
            duration:1000,
            position: "bottom-right",
          });
    }

    function clear(){
        itemCount.value = 0;
        items.value = [];
        total.value = 0;
    }

    return { itemCount, items , addItem, removeItem, total, clear}

})