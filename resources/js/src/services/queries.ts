import { useQueries, useQuery, useQueryClient, useMutation } from '@tanstack/vue-query';
import { ref, watchEffect, computed, type MaybeRef, unref } from 'vue';
import { getCountApproval, getListApproval, getNotes, getPurchaseRequestIds, getSubmission, getSubmissionDetail, postSubmission } from './api/submissionService';
import type { SubmissionListParams, SubmissionListResponse } from '@/types/submission';
import type { AssetDetail } from '@/types/report';
import { getCheckUser } from './api/loginService';
import { getPurchaseRequestDetail } from './api/purchaseRequestService';
import { getCabangList, getFileList, getMasterBrg, getVendorList, getKategoriList, getTipeBarangList, getMerkList, createMerk, updateMerk, deleteMerk } from './api/masterService';
import { getListApproved } from './api/submissionService';
import { getAssetByCabangPaginated, getAssetDetail, getAssetReport, getAssetReportPaginated, getBodyStatistik, getHeaderStatistik, getReportBarcode, getReportByCabang } from './api/reportService';

export const usePurchaseRequestIds = () => {
    return useQuery({
        queryKey: ['PurchaseRequestIds'],
        queryFn: getPurchaseRequestIds,
    });
};

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

export const useListSubmission = (type?: MaybeRef<string>, requestBy?: MaybeRef<string>) => {
    return useQuery({
        queryKey: ['submissions', type, requestBy],
        queryFn: () => getSubmission(unref(type) ?? ''),
        staleTime: 1000 * 60,
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
        },
    });
};

export const useCheckUser = () => {
    return useQuery({
        queryKey: ['userCheck'],
        queryFn: getCheckUser,
        // select: (response) => response,
        staleTime: 1000 * 60 * 5,
        refetchOnWindowFocus: false,
    });
};

export const useDetailSubmission = (formType: MaybeRef<string>, requestNumber: MaybeRef<string>) => {
    return useQuery({
        queryKey: ['submissionDetail', unref(formType), unref(requestNumber)],
        queryFn: () => getSubmissionDetail(unref(formType), unref(requestNumber)),
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
    console.log('formNumber in useGetFileList:', formNumber);
    return useQuery({
        queryKey: ['fileList', unref(formNumber)],
        queryFn: () => getFileList(unref(formNumber)),
        // cuma run jika formNumber ada
        enabled: computed(() => !!formNumber),
        // jika response shape { data: [...] }, pilih langsung arraynya
        select: (res) => res.data,
        staleTime: 1000 * 60, // 1 menit
    });
};

export const useGetApprovalList = (type: string, username?: MaybeRef<string>) => {
  return useQuery({
    queryKey: ['approvalList', type, unref(username)],
    queryFn: () => getListApproval(type),
  })
}

// START: PURCHASE ORDER
export const useGetListApproved = (formType: string) => {
  
    console.log('Fetching approved list for formType:', formType);
    return useQuery({
        queryKey: ['listApprovedPR', formType],
        queryFn: async () => {
          const listApproved = await getListApproved(formType);
          const cabang = await getCabangList();
          
          const detailedCabangName = await Promise.all(listApproved.map(async (pr) => {
            const nameCabang = cabang.find((cabang) => cabang.id_area === pr.cabang) ?? null;
            console.log(`Matching PR ${pr.pr_number} with Cabang:`, nameCabang);
            return {
              ...pr,
              nama_cabang: pr.cabang === '9999' ? 'HO' : nameCabang ? nameCabang.nm_area : '-- tidak ditemukan --',
            };
          }));
          
          console.log('Detailed Cabang Name List:', detailedCabangName);
          return detailedCabangName;
        }
    });
};
// END: PURCHASE ORDER

// START: REGISTER ASSET
export const useGetListApprovedPO = () => {
    return useQuery({
        queryKey: ['listApprovedPO'],
        queryFn: () => getListApproved(),
    });
};
// END: REGISTER ASSET

// START: Master
export const useGetVendorList = () => {
    return useQuery({
        queryKey: ['vendorList'],
        queryFn: () => getVendorList(),
    });
};
// END: Master

// START: Asset Report
export const useGetAssetReport = () => {
    return useQuery({
        queryKey: ['assetReport'],
        queryFn: () => getAssetReport(),
    });
};

export const useGetAssetDetail = (asset_number: string) => {
    return useQuery<AssetDetail>({
        queryKey: ['assetDetail', asset_number],
        queryFn: () => getAssetDetail(asset_number),
    });
}


export const useGetAssetReportPaginated = (limitRef, offsetRef) => {
  const params = ref({ limit: limitRef.value, offset: offsetRef.value });

  watchEffect(() => {
    params.value = { limit: limitRef.value, offset: offsetRef.value };
  });

  return useQuery({
    queryKey: ['assetReportPaginated', params],
    queryFn: () => getAssetReportPaginated(params.value),
    keepPreviousData: true,
  });
};

export const useGetAssetByCabangPaginated = (cabangRef = ref(''), kapitalisRef = ref(''), limitRef, offsetRef) => {
    const params = ref({ cabang_id: cabangRef.value, tipe_aset: kapitalisRef.value, limit: limitRef.value, offset: offsetRef.value });
    watchEffect(() => {
        params.value = { cabang_id: cabangRef.value, tipe_aset: kapitalisRef.value, limit: limitRef.value, offset: offsetRef.value };
    });
    return useQuery({
        queryKey: ['assetByCabangPaginated', params],
        queryFn: () => getAssetByCabangPaginated(params.value),
        keepPreviousData: true,
    });
};

export const useGetReportBarcode = () => {
    return useQuery({
        queryKey: ['reportBarcode'],
        queryFn: () => getReportBarcode(),
    });
};
// END: Asset Report

// START: Notes
export const useGetNotes = (formType: string, formNumber: string) => {
    return useQuery({
        queryKey: ['notes', formType, formNumber],
        queryFn: async () => {
            const response = await getNotes(formType, formNumber);

            console.log('API getNotes response:', response.data);
            return response.data;
        },
    });
};
// End: Notes

// Master Cabang and Area can be found in masterService.ts
export const useGetCabangList = () => {
    return useQuery({
        queryKey: ['cabangList'],
        queryFn: () => getCabangList(),
    });
};

export const useGetItemMasterList = () => {
    return useQuery({
        queryKey: ['itemMasterList'],
        queryFn: () => getMasterBrg(),
    });
};
export const useGetKategoriList = () => {
    return useQuery({
        queryKey: ['kategoriList'],
        queryFn: () => getKategoriList(),
    });
};
export const useGetTipeBarangList = () => {
    return useQuery({
        queryKey: ['tipeBarangList'],
        queryFn: () => getTipeBarangList(),
    });
};
export const useGetMerkList = () => {
    return useQuery({
        queryKey: ['merkList'],
        queryFn: () => getMerkList(),
    });
};

export const useCreateMerk = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: createMerk,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['merkList'] });
        },
    });
};

export const useUpdateMerk = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: ({ id, data }: { id: number; data: { nama_merkbrg: string } }) => updateMerk(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['merkList'] });
        },
    });
};

export const useDeleteMerk = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: deleteMerk,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['merkList'] });
        },
    });
};

export const useCountApproval = (username: string) => {
    return useQuery({
        queryKey: ['countApproval', username],
        queryFn: () => getCountApproval(username),
    });
};

export const useGetReportByCabangQuery = (data: any) => {
    console.log('report data cabang', data);
    return useQuery({
        queryKey: ['reportByCabang', data],
        queryFn: () => getReportByCabang(data),
    });
}

export const useGetHeaderStatistik = () => {
    return useQuery({
        queryKey: ['headerStatistik'],
        queryFn: () => getHeaderStatistik(),
    });
}

export const useGetBodyStatistik = () => {
    return useQuery({
        queryKey: ['bodyStatistik'],
        queryFn: () => getBodyStatistik(),
    });
}