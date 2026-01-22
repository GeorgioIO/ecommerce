export async function fetch_formats_DB() {
  const result = await fetch("../../backend/formats/get_formats.php");
  return result.json();
}
