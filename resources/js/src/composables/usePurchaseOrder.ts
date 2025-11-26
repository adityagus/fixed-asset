import { computed } from 'vue';
export function usePurchaseOrder(formType, formData, vendorList) {
  return computed(() => {
    if (
      formType.value === 'purchase-order' &&
      formData.value.status &&
      formData.value.status.toLowerCase() !== 'draft'
    ) {
      return {
        ...formData.value,
        vendorName: vendorList.value?.find(v => v.id === formData.value.vendor_id)?.nama || '',
        prLocked: true,
      };
    }
    return null;
  });
}
