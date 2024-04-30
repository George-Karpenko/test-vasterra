import { replaceKeysCamelToSnakeCase } from "@/helpers";
import api from "./http";

export async function list() {
  return await api.get("return-of-product");
}

export function store(data) {
  return api.post("return-of-product", replaceKeysCamelToSnakeCase(data));
}

