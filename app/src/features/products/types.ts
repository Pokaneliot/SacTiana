export interface Category {
  id: number;
  name: string;
}

export interface Product {
  id: number;
  ref: string;
  name: string | null;
  purchasePrice: number;
  sellingPrice: number;
  createdAt: string;
  quantity: number | null;
  category: Category;
  lastUpdateAt: string | null;
}

export interface CreateProductRequest {
  ref: string;
  name?: string;
  purchasePrice: number;
  sellingPrice: number;
  categoryId: number;
}

export interface UpdateProductRequest {
  ref?: string;
  name?: string;
  purchasePrice?: number;
  sellingPrice?: number;
}

export interface ProductFilter {
  ref?: string;
  sellingPrice?: number;
  createdAt?: string;
  categoryId?: number;
}
