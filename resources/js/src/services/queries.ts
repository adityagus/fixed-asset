import { useQueries, useQuery, useQueryClient, useMutation } from "@tanstack/vue-query"
import { computed, type MaybeRef, unref } from "vue"
import { getListApproval, getNotes, getPurchaseRequestIds, getSubmission, getSubmissionDetail, postSubmission } from "./api/submissionService";
import type { SubmissionListParams, SubmissionListResponse } from '@/types/submission';
import { getCheckUser } from "./api/loginService";
import {
  createPurchaseRequest,
  saveDraftPurchaseRequest,
  submitPurchaseRequest,
  getPurchaseRequestDetail,
} from "./api/purchaseRequestService";
import { getFileList, getMasterBrg, getVendorList } from "./api/masterService";
import { getListApproved } from "./api/submissionService";
import { getAssetReport, getReportBarcode } from "./api/reportService";

export const usePurchaseRequestIds = () => {
  return useQuery({
    queryKey: ['PurchaseRequestIds'],
    queryFn: getPurchaseRequestIds
  })
}


// SUBMISSION
// Query Keys
export const submissionKeys = {
  all: ['submissions'] as const,
  lists: () => [...submissionKeys.all, 'list'] as const,
  list: (params?: SubmissionListParams) => [...submissionKeys.lists(), params] as const,
};

// Hook: Fetch all submissions
// export const useSubmissions = (params?: string) => {
//   // console.log('Fetching submissions with params:', unref(params));
//   // const unwrappedParams = computed(() => unref(params));
  
//   // return useQuery<SubmissionListResponse, Error>({
//   //   queryKey: computed(() => submissionKeys.all),
//   //   queryFn: () => getSubmission(unwrappedParams.value ? JSON.parse(unwrappedParams.value) : undefined),
//   //   staleTime: 1000 * 60 * 5, // 5 minutes
//   // });
//   return useQuery({
//     queryKey: computed(() => submissionKeys.all),
//     queryFn: () => getSubmission(params ? JSON.parse(params) : undefined),
//     staleTime: 1000 * 60 * 5, // 5 minutes
//   });
  
// };

export const useListSubmission = (type?: MaybeRef<string>) => {
  return useQuery({
    queryKey: ['submissions', type],
    queryFn: () => getSubmission(unref(type) ?? ''),
    staleTime: 1000 * 60 * 5,
  });
};

export const useFormSubmission = () => {
  return useMutation({
    mutationFn: postSubmission,
    onSuccess: (data) => {
      // invalidate or refetch queries if needed
      // queryClient.invalidateQueries(['formSubmission']);
      console.log('Form submission success:', data);
    },
    onError: (error) => {
      console.error('Error submitting form:', error);
    }
  });
}

export const useCheckUser = () => {
  return useQuery({
    queryKey: ['userCheck'],
    queryFn: getCheckUser,
    // select: (response) => response,
    staleTime: 1000 * 60 * 5,
    retry: 1,
    refetchOnWindowFocus: false,
  });
}

export const useDetailSubmission = (formType: MaybeRef<string>, requestNumber: MaybeRef<string>) => {
  return useQuery({
    queryKey: ['submissionDetail', unref(formType), unref(requestNumber)],
    queryFn: () => getSubmissionDetail(unref(formType), unref(requestNumber)),
  });
}

export const useCreatePurchaseRequest = () => {
  return useMutation({
    mutationFn: createPurchaseRequest,
  });
};

export const useSaveDraftPurchaseRequestItems = () => {
  return useMutation({
    mutationFn: (formData) => saveDraftPurchaseRequest(formData),
  });
};


export const usePurchaseRequestDetail = (pr_number: string) => {
  return useQuery({
    queryKey: ['purchaseRequestDetail', pr_number],
    queryFn: () => getPurchaseRequestDetail(pr_number),
    enabled: !!pr_number,
  });
};

export const useGetMasterBrg = () => {
  return useQuery({
    queryKey: ['masterBrg'],
    queryFn: () => getMasterBrg(),
  });
};

export const useGetFileList = (formNumber: string) => {
  console.log("formNumber in useGetFileList:", formNumber);
  return useQuery({
    queryKey: ['fileList', unref(formNumber)],
    queryFn: () => getFileList(unref(formNumber)),
    // cuma run jika formNumber ada
    enabled: computed(() => !!formNumber),
    // jika response shape { data: [...] }, pilih langsung arraynya
    select: (res) => res.data,
    staleTime: 1000 * 60, // 1 menit
  });
}


export const useGetApprovalList = (type: string) => {
  return useQuery({
    queryKey: ['approvalList', type],
    queryFn: () => getListApproval(type),
    enabled: computed(() => !!type),
    staleTime: 1000 * 60, // 1 menit
  })
}

// START: PURCHASE ORDER
export const useGetListApproved = ({formType}) => {
  return useQuery({
    queryKey: ['listApprovedPR', formType],
    queryFn: () => getListApproved(formType),
  })
}
// END: PURCHASE ORDER

// START: REGISTER ASSET
export const useGetListApprovedPO = () => {
  return useQuery({
    queryKey: ['listApprovedPO'],
    queryFn: () => getListApproved(),
  })
}
// END: REGISTER ASSET



// START: Master
export const useGetVendorList = () => {
  return useQuery({
    queryKey: ['vendorList'],
    queryFn: () => getVendorList(),
  })
}
// END: Master


// START: Asset Report
export const useGetAssetReport = () => {
  return useQuery({
    queryKey: ['assetReport'],
    queryFn: () => getAssetReport(),
  })
}

export const useGetReportBarcode = () => {
    return useQuery({
        queryKey: ['reportBarcode'],
        queryFn: () => getReportBarcode(),
    })
}

// END: Asset Report

// START: Notes
export function useGetNotes(formType, formNumber) {
  return useQuery(
    {
        queryKey: ['notes', formType, formNumber],
        queryFn: async () => {
            const response = await getNotes(formType, formNumber);
            
            console.log('API getNotes response:', response.data);
            return response.data;
        }
    }
  )
}
// End: Notes
