export async function getFormats_DB() {
  const result = await fetch("../../backend/formats/get_formats.php");
  return result.json();
}
