export function camelCaseToSnakeCase(str) {
  return str.replace(/([A-Z])/g, "_$1").toLowerCase();
}
export function replaceKeysCamelToSnakeCase(obj) {
  const newObject = {};
  for (const camel in obj) {
    newObject[camelCaseToSnakeCase(camel)] = obj[camel];
  }
  return newObject;
}

