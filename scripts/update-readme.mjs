import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const tipsRoot = path.resolve(__dirname, '..');

const KNOWN_WORDS = {
  'php': 'PHP',
  'css': 'CSS',
  'api': 'API',
  'dom': 'DOM',
  'mysql': 'MySQL',
  'sql': 'SQL',
  'ui': 'UI',
  'cli': 'CLI',
  'rss': 'RSS',
  'html': 'HTML',
  'http': 'HTTP',
  'js': 'JS',
  'ts': 'TS',
  'url': 'URL',
};

function formatTitle(slug) {
  return slug
    .split('-')
    .map(word => {
      const lower = word.toLowerCase();
      if (KNOWN_WORDS[lower]) return KNOWN_WORDS[lower];
      return word.charAt(0).toUpperCase() + word.slice(1);
    })
    .join(' ')
    .replace('HTTP API', 'HTTP & API');
}

function generateIndexMarkdown(root) {
  const contentDir = path.join(root, 'content');
  if (!fs.existsSync(contentDir)) return { totalTips: 0, markdown: '' };

  const categories = fs.readdirSync(contentDir, { withFileTypes: true })
    .filter(d => d.isDirectory())
    .map(d => d.name);

  const catData = [];
  let totalTips = 0;

  for (const cat of categories) {
    const catPath = path.join(contentDir, cat);
    const entries = fs.readdirSync(catPath, { withFileTypes: true });
    const subdirs = entries.filter(d => d.isDirectory()).map(d => d.name);

    const subList = [];
    let catCount = 0;

    for (const sub of subdirs.sort()) {
      const subPath = path.join(catPath, sub);
      const files = fs.readdirSync(subPath).filter(f => f.endsWith('.md') && f.toLowerCase() !== 'readme.md');
      if (files.length > 0) {
        subList.push({
          slug: sub,
          title: formatTitle(sub),
          count: files.length,
          relPath: `content/${cat}/${sub}`,
        });
        catCount += files.length;
      }
    }

    if (catCount > 0) {
      catData.push({
        slug: cat,
        title: formatTitle(cat),
        count: catCount,
        subcategories: subList,
      });
      totalTips += catCount;
    }
  }

  // Sort categories by tip count descending, then alphabetical
  catData.sort((a, b) => b.count - a.count || a.title.localeCompare(b.title));

  const lines = [];
  for (const cat of catData) {
    lines.push(`- **${cat.title} (${cat.count})**`);
    for (const sub of cat.subcategories) {
      lines.push(`  - [${sub.title} (${sub.count})](${sub.relPath})`);
    }
  }

  return { totalTips, markdown: lines.join('\n') };
}

function updateReadme() {
  const readmePath = path.join(tipsRoot, 'README.md');
  if (!fs.existsSync(readmePath)) {
    console.error('README.md not found at', readmePath);
    process.exit(1);
  }

  const { totalTips, markdown } = generateIndexMarkdown(tipsRoot);
  let readmeContent = fs.readFileSync(readmePath, 'utf-8');

  const startMarker = '<!-- TIPS_INDEX:START -->';
  const endMarker = '<!-- TIPS_INDEX:END -->';

  const newBlock = `${startMarker}\n${markdown}\n${endMarker}`;

  if (readmeContent.includes(startMarker) && readmeContent.includes(endMarker)) {
    const startIndex = readmeContent.indexOf(startMarker);
    const endIndex = readmeContent.indexOf(endMarker) + endMarker.length;
    readmeContent = readmeContent.slice(0, startIndex) + newBlock + readmeContent.slice(endIndex);
  } else {
    const heading = '## Category & Subcategory Directory Index';
    if (readmeContent.includes(heading)) {
      const headingIndex = readmeContent.indexOf(heading);
      const nextHeadingIndex = readmeContent.indexOf('\n## ', headingIndex + heading.length);
      const before = readmeContent.slice(0, headingIndex);
      const after = nextHeadingIndex !== -1 ? readmeContent.slice(nextHeadingIndex) : '';
      readmeContent = `${before}${heading}\n\nExplore ${totalTips} engineering tips directly in the repository by category:\n\n${newBlock}\n${after}`;
    }
  }

  readmeContent = readmeContent.replace(
    /Explore (?:\*\*)?\d+(?:\*\*)? engineering tips/g,
    `Explore **${totalTips}** engineering tips`
  );

  fs.writeFileSync(readmePath, readmeContent, 'utf-8');
  console.log(`✅ README.md updated successfully with ${totalTips} tips across categories and subcategories.`);
}

updateReadme();
