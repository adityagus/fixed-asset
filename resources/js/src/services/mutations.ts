import { useMutation, useQueryClient } from "@tanstack/vue-query";
// import { createSubmission } from './api';
import { deleteSubmission, editSubmission, getSubmission, postSendNote, postSubmission } from "./api/submissionService";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";
import {
    postUploadFile,
    createMasterBrg,
    updateMasterBrg,
    deleteMasterBrg,
    createKategori,
    updateKategori,
    deleteKategori,
    createTipeBarang,
    updateTipeBarang,
    deleteTipeBarang,
    createVendor,
    updateVendor,
    deleteVendor,
    createMerk,
    updateMerk,
    deleteMerk,
} from "./api/masterService";
import { saveDraftPurchaseRequest, submitPurchaseRequest } from "./api/purchaseRequestService";
import { PurchaseRequest, SubmitPurchaseRequestPayload } from "@/types/purchaseRequest";
import { axiosInstance } from "@/utils/axios";
import { SubmitPurchaseOrderPayload } from "@/types/purchaseOrders";
import { submitPurchaseOrder } from "./api/purchaseOrderService";
import { SubmitRegisterAssetPayload } from "@/types/registerAsset";
import { submitRegistrationAsset } from "./api/registerAssetService";
import { logout } from "./api/loginService";
import { editSubmissionPayload } from "@/types/submission";

// const queryClient = useQueryClient();
// export const useCreateSubmission = () => {

//   return useMutation({
//     mutationFn: getSubmission,
//     onSuccess: () => {
//       // Invalidate and refetch
//       queryClient.invalidateQueries(['submissions']);
//     },
//     onError: (error) => {
//       console.error('Error creating submission:', error);
//     },
//     onMutate(variables, context) {
//       console.log('Creating submission with variables:', variables);
//       return context;
//     },
//     onSettled() {
//       console.log('Create submission mutation settled');
//     }
//   });
// }

export const useCreateFormSubmission = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (typeFormat) => postSubmission(typeFormat),
        onSuccess: (res) => {
            console.log("Form submission created successfully", res);
            window.location.href = `/apps/form/${res.type}/${res.data.pr_number || res.data.po_number || res.data.ra_number}`;

            // router.push('/apps/form-builder/' + res.data.pr_number);
            // Invalidate and refetch
            // queryClient.invalidateQueries(['submissions']);
        },
        onError: (error) => {
            console.error("Error creating form submission:", error);
        },
        onSettled: async (_, error) => {
            if (error) {
                console.error("Error creating form submission:", error);
            } else {
                // await queryClient.invalidateQueries(['submissions']);
            }
            console.log("Create form submission mutation settled");
        },
    });
};

export const useDeleteSubmission = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: ({ type, number }) => {
            console.log("useDeleteSubmission called with:", type, number);
            return deleteSubmission(type, number);
        },
        onSuccess: async () => {
            await queryClient.invalidateQueries(["submissions"]);
            Swal.fire("Deleted!", `Submission has been deleted.`, "success");
        },
        onError: (error) => {
            console.error("Error deleting submission:", error);
        },
        onSettled: () => {
            console.log("Delete submission mutation settled");
        },
    });
};

export const useEditSubmission = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (payload: editSubmissionPayload) => editSubmission(payload),
        onSuccess: async (data, variable) => {
            console.log("Submission edited successfully:", data);
            console.log("Invalidating queries for:", variable);
            queryClient.invalidateQueries({
                queryKey: ["submissionDetail", variable.type, variable.number],
            });
            queryClient.invalidateQueries({
                queryKey: ["submissions", variable.type, variable.requestedBy],
            });
            Swal.fire("Edited!", `Submission has been edited.`, "success");
        },
        onError: (error) => {
            console.error("Error deleting submission:", error);
        },
        onSettled: () => {
            console.log("Delete submission mutation settled");
        },
    });
};

export const useUploadFileSubmission = (type: string) => {
    return useMutation({
        mutationFn: (formData: FormData) => postUploadFile(type, formData),
    });
};

export const useSubmitPurchaseRequest = () => {
    const qc = useQueryClient();
    const router = useRouter();
    // console.log('useSubmitPurchaseRequest called', formData);
    return useMutation({
        mutationFn: async (formData: SubmitPurchaseRequestPayload) => await submitPurchaseRequest(formData),
        onSuccess: (data, variable) => {
            console.log("Purchase Request submitted successfully:", data, "variable", variable);
            qc.invalidateQueries({
                queryKey: ["submissionDetail", "purchase-request", variable.formNumber],
            });
            qc.invalidateQueries({
                queryKey: ["submissions", "purchase-request", variable.requestedBy],
            });

            router.push("/submission").then(() => {
                Swal.fire({
                    title: "Sukses!",
                    text: `Permintaan Pembelian (${variable.formNumber}) telah berhasil dikirim!`,
                    icon: "success",
                    confirmButtonText: "OK",
                    timer: 1500,
                    // after runing  router push
                });
            });
        },
        onError: (error) => {
            console.error("Error submitting Purchase Request:", error);
            Swal.fire({
                title: "Error!",
                text: `Gagal mengirim Permintaan Pembelian: ${error.response.data.message}`,
                icon: "error",
                confirmButtonText: "OK",
            });
        },
        onSettled: () => {
            console.log("Submit Purchase Request mutation settled");
        },
    });
};

export const useSubmitPurchaseOrder = () => {
    const qc = useQueryClient();
    const router = useRouter();
    return useMutation({
        mutationFn: async (formData: SubmitPurchaseOrderPayload) => await submitPurchaseOrder(formData),
        onSuccess: (data, variable) => {
            console.log("Purchase Order submitted successfully:", data);
            qc.invalidateQueries({
                queryKey: ["submissionDetail", "purchase-request", variable.formNumber],
            });
            qc.invalidateQueries({
                queryKey: ["submissions", "purchase-request", variable.requestedBy],
            });

            router.push("/submission").then(() => {
                Swal.fire({
                    title: "Sukses!",
                    text: `Pesanan Pembelian (${variable.po_number}) telah berhasil dikirim!`,
                    icon: "success",
                    confirmButtonText: "OK",
                    timer: 1500,
                });
            });
        },
        onError: (error) => {
            console.error("Error submitting Purchase Order:", error.response.data.message);
            Swal.fire({
                title: "Error!",
                text: `Gagal mengirim Pesanan Pembelian: ${error.response.data.message}`,
                icon: "error",
                confirmButtonText: "OK",
            });
        },
        onSettled: () => {
            console.log("Submit Purchase Order mutation settled");
        },
    });
};

export const useSubmitRegistrationAsset = () => {
    const qc = useQueryClient();
    const router = useRouter();
    return useMutation({
        mutationFn: async (formData: SubmitRegisterAssetPayload) => await submitRegistrationAsset(formData),
        onSuccess: (data, variable) => {
            console.log("Registration Asset submitted successfully:", data);
            qc.invalidateQueries({
                queryKey: ["submissionDetail", "register-asset", variable.formNumber],
            });
            qc.invalidateQueries({
                queryKey: ["submissions", "register-asset", variable.requestedBy],
            });

            router.push("/submission").then(() => {
                Swal.fire({
                    title: "Sukses!",
                    text: `Pendaftaran Aset (${variable.ra_number}) telah berhasil dikirim!`,
                    icon: "success",
                    confirmButtonText: "OK",
                    timer: 1500,
                });
            });
        },
        onError: (error) => {
            console.error("Error submitting Registration Asset:", error.response.data.messages);
            Swal.fire({
                title: "Error!",
                text: `Gagal mengirim Pendaftaran Aset: ${error.response.data.message}`,
                icon: "error",
                confirmButtonText: "OK",
            });
        },
        onSettled: () => {
            console.log("Submit Registration Asset mutation settled");
        },
    });
};

export const useSaveDraftPurchaseRequest = () => {
    const qc = useQueryClient();
    return useMutation({
        mutationFn: async (formData: SubmitPurchaseRequestPayload) => await saveDraftPurchaseRequest(formData),
        onSuccess: (data) => {
            console.log("Purchase Request draft saved successfully:", data);
            qc.invalidateQueries(["PurchaseRequestIds"]);
        },
        onError: (error) => {
            console.error("Error saving Purchase Request draft:", error);
        },
        onSettled: () => {
            console.log("Save Purchase Request draft mutation settled");
        },
    });
};

export const useSetApprovalStatus = () => {
    const qc = useQueryClient();
    const router = useRouter();
    return useMutation({
        mutationFn: async (formData: { formNumber: number; layer: number; status: "approved" | "rejected"; type: string; usernameApprover?: string }) => {
            const response = await axiosInstance.post<any>(`/set-approval`, formData);
            return response.data;
        },
        onSuccess: (data: any, formData: { formNumber: number; layer: number; status: "approved" | "rejected"; type: string; usernameApprover?: string }) => {
            qc.invalidateQueries({ queryKey: ["approvalList", formData.type] });
            qc.invalidateQueries({ queryKey: ["countApproval", formData.usernameApprover] });
            let approvalPath = "";
            if (formData.type === "purchase-request") {
                approvalPath = "pr-approval";
            } else if (formData.type === "purchase-order") {
                approvalPath = "po-approval";
            } else if (formData.type === "registration-asset") {
                approvalPath = "ra-approval";
            }
            router.push(`/approval/${approvalPath}`);
        },
        onError: (error) => {
            console.error("Error setting approval status:", error);
        },
        onSettled: () => {
            console.log("Set approval status mutation settled");
        },
    });
};

export const useSendNote = () => {
    const qc = useQueryClient();
    return useMutation({
        mutationFn: async (formData: { formNumber: string; formType: string; text: string; sender: string; time: string }) => {
            await postSendNote(formData);
        },
        onSuccess: (data) => {
            console.log("Note sent successfully:", data);
            qc.invalidateQueries(["notes"]);
        },
        onError: (error) => {
            console.error("Error sending note:", error);
        },
        onSettled: () => {
            console.log("Send note mutation settled");
        },
    });
};

export const useLogout = () => {
    return useMutation({
        mutationFn: async () => {
            await logout();
        },
    });
};

// Master Barang CUD Mutations
export const useCreateMasterBarang = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (data: any) => createMasterBrg(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["masterBrg"] });
            Swal.fire("Berhasil!", "Barang berhasil ditambahkan.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal menambah barang.", "error");
        },
    });
};

export const useUpdateMasterBarang = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: ({ id, data }: { id: number; data: any }) => updateMasterBrg(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["masterBrg"] });
            Swal.fire("Berhasil!", "Barang berhasil diupdate.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal update barang.", "error");
        },
    });
};

export const useDeleteMasterBarang = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (id: number) => deleteMasterBrg(id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["masterBrg"] });
            Swal.fire("Berhasil!", "Barang berhasil dihapus.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal hapus barang.", "error");
        },
    });
};

// Master Kategori CUD Mutations
export const useCreateKategori = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (data: any) => createKategori(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["kategoriList"] });
            Swal.fire("Berhasil!", "Kategori berhasil ditambahkan.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal menambah kategori.", "error");
        },
    });
};

export const useUpdateKategori = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: ({ id, data }: { id: number; data: any }) => updateKategori(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["kategoriList"] });
            Swal.fire("Berhasil!", "Kategori berhasil diupdate.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal update kategori.", "error");
        },
    });
};

export const useDeleteKategori = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (id: number) => deleteKategori(id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["kategoriList"] });
            Swal.fire("Berhasil!", "Kategori berhasil dihapus.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal hapus kategori.", "error");
        },
    });
};

// Master Tipe Barang CUD Mutations
export const useCreateTipeBarang = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (data: any) => createTipeBarang(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["tipeBarangList"] });
            Swal.fire("Berhasil!", "Tipe barang berhasil ditambahkan.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal menambah tipe barang.", "error");
        },
    });
};

export const useUpdateTipeBarang = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: ({ id, data }: { id: number; data: any }) => updateTipeBarang(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["tipeBarangList"] });
            Swal.fire("Berhasil!", "Tipe barang berhasil diupdate.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal update tipe barang.", "error");
        },
    });
};

export const useDeleteTipeBarang = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (id: number) => deleteTipeBarang(id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["tipeBarangList"] });
            Swal.fire("Berhasil!", "Tipe barang berhasil dihapus.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal hapus tipe barang.", "error");
        },
    });
};

// Master Vendor CUD Mutations
export const useCreateVendor = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (data: any) => createVendor(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["vendorList"] });
            Swal.fire("Berhasil!", "Vendor berhasil ditambahkan.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal menambah vendor.", "error");
        },
    });
};

export const useUpdateVendor = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: ({ id, data }: { id: number; data: any }) => updateVendor(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["vendorList"] });
            Swal.fire("Berhasil!", "Vendor berhasil diupdate.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal update vendor.", "error");
        },
    });
};

export const useDeleteVendor = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (id: number) => deleteVendor(id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["vendorList"] });
            Swal.fire("Berhasil!", "Vendor berhasil dihapus.", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error?.response?.data?.message || "Gagal hapus vendor.", "error");
        },
    });
};
// Master Merk CUD Mutations
export const useCreateMerk = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (data: { nama_merkbrg: string }) => createMerk(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["merkList"] });
            Swal.fire("Berhasil!", "Merek berhasil ditambahkan", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error.message || "Gagal menambahkan merek", "error");
        },
    });
};

export const useUpdateMerk = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: ({ id, data }: { id: number; data: { nama_merkbrg: string } }) => updateMerk(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["merkList"] });
            Swal.fire("Berhasil!", "Merek berhasil diupdate", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error.message || "Gagal mengupdate merek", "error");
        },
    });
};

export const useDeleteMerk = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (id: number) => deleteMerk(id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ["merkList"] });
            Swal.fire("Berhasil!", "Merek berhasil dihapus", "success");
        },
        onError: (error: any) => {
            Swal.fire("Error!", error.message || "Gagal menghapus merek", "error");
        },
    });
};
