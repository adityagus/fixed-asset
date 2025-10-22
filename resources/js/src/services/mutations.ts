 import { useMutation, useQueryClient } from '@tanstack/vue-query';
import { createSubmission } from './api';


export const useCreateSubmission = () => {
  return useMutation({
    mutationFn: createSubmission,
    onSuccess: () => {
      // Invalidate and refetch
      queryClient.invalidateQueries(['submissions']);
    },
    onError: (error) => {
      console.error('Error creating submission:', error);
    },
    onMutate(variables, context) {
      console.log('Creating submission with variables:', variables);
      return context;
    },
    onSettled() {
      console.log('Create submission mutation settled');
    }
  });
}