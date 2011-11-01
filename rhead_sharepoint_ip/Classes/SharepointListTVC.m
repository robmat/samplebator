#import "SharepointListTVC.h"
#import "WebViewVC.h"
#import "CXMLElement.h"
#import "SoapRequest.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "SharepointListVC.h"

@implementation SharepointListTVC

@synthesize listsData, keysArr, navCntrl, myListName, delegate, currentFolder;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.keysArr = [listsData allKeys];
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 64.0;
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [listsData count];
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {
	[cell setBackgroundColor:[UIColor colorWithRed:0.195 green:0.234 blue:0.437 alpha:1]];
}
- (UITableViewCell *)tableView:(UITableView *)tableView_ cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    SharepointListCell* cell = nil;
	if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"SharepointListCell" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (SharepointListCell*) currentObject;
                break;
            }
        }
    }
    NSString* key = [keysArr objectAtIndex:indexPath.row];
	NSDictionary* dict = [listsData objectForKey:key];
	cell.titleLbl.text = [dict objectForKey:@"ows_Title"];
	cell.dateLbl.text = [dict objectForKey:@"ows_Created"];
	cell.icon.hidden = YES;
    cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
	NSString* url = [dict objectForKey:@"ows_EncodedAbsUrl"];
	if ([self str:url contains:@"png"] ||
		[self str:url contains:@"gif"] ||
		[self str:url contains:@"jpg"] ||
		[self str:url contains:@"jpeg"] ||
		[self str:url contains:@"bmp"] ||
		[self str:currentFolder contains:@"Site Progress Photos"]) {
		cell.icon.hidden = NO;
		cell.icon.image = [UIImage imageNamed:@"photoicon.png"];
	}
	if ([self str:url contains:@"pdf"]) {
		cell.icon.hidden = NO;
		cell.icon.image = [UIImage imageNamed:@"pdficon.png"];
	}
	if ([self str:url contains:@"doc"] || [self str:url contains:@"docx"]) {
		cell.icon.hidden = NO;
		cell.icon.image = [UIImage imageNamed:@"pdficon.png"];
	}
	if ([self str:url contains:@"ppt"] || [self str:url contains:@"pptx"]) {
		cell.icon.hidden = NO;
		cell.icon.image = [UIImage imageNamed:@"ppticon.png"];
	}
	if ([self str:url contains:@"xls"] || [self str:url contains:@"xlsx"]) {
		cell.icon.hidden = NO;
		cell.icon.image = [UIImage imageNamed:@"excelicon.png"];
	}
	if ([[dict  objectForKey:@"ows_ContentType"] isEqualToString:@"Folder"]) {
		cell.icon.hidden = NO;
		cell.icon.image = [UIImage imageNamed:@"foldericon.png"];
	}
	return cell;
}
- (BOOL)str: (NSString*) str contains: (NSString*) con {
	return [str rangeOfString:con].location != NSNotFound;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	[UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
	[[tableView cellForRowAtIndexPath:indexPath] setSelected:NO];
	NSString* key = [keysArr objectAtIndex:indexPath.row];
	indexPressed = indexPath;
	if ([[[listsData objectForKey:key] objectForKey:@"ows_ContentType"] isEqualToString:@"Folder"]) {
		NSDictionary* loginDict = [NSDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate loginDictPath]];
		NSString* domain = [loginDict objectForKey:@"domain"];
		NSString* login = [loginDict objectForKey:@"login"];
		NSString* password = [loginDict objectForKey:@"password"];
		NSURL* url = [NSURL URLWithString:[NSString stringWithFormat:@"%@%@", domain, @"/_vti_bin/Lists.asmx"]];
		NSString* host = [[NSURL URLWithString:domain] host];
		domain = [host stringByReplacingOccurrencesOfString:@"www." withString:@""];
		NSString* envelope = [NSString stringWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"GetListItems" ofType:@"txt"] 
													   encoding: NSUTF8StringEncoding 
														  error:nil];
		NSString* folder = [NSString stringWithFormat:@"%@%@/", currentFolder,[[listsData objectForKey:key] objectForKey:@"ows_Title"]];
		NSString* queryOptions = [NSString stringWithFormat:@"<Folder>%@</Folder>", folder];
		envelope = [NSString stringWithFormat:envelope, myListName, @"99999", queryOptions];
		SoapRequest* soapReq = [[SoapRequest alloc] initWithUrl:url 
													   username:login 
													   password:password 
														 domain:domain 
													   delegate:self 
													   envelope:envelope action:@"http://schemas.microsoft.com/sharepoint/soap/GetListItems"];
		[soapReq startRequest];
	} else {
		WebViewVC* wvvc = [[WebViewVC alloc] initWithNibName:nil bundle:nil];
		wvvc.url = key;
		[self.navCntrl pushViewController:wvvc animated:YES];
		SharepointListCell* cell = (SharepointListCell*) [tableView cellForRowAtIndexPath:indexPath];
		[wvvc setTitle: [[cell titleLbl] text]];
		[wvvc release];
	}
}
- (void) requestFinishedWithXml: (CXMLDocument*) doc {
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
	NSLog(@"%@", [doc description]);
	NSArray* listsNodes = [doc nodesForXPath:@"/Envelope/Body/GetListItemsResponse/GetListItemsResult/listitems/data/row" error:nil];
	NSMutableDictionary* listDict = [NSMutableDictionary dictionary];
	for (CXMLElement* listNode in listsNodes) {
		CXMLNode* nameAttr = [listNode attributeForName:@"ows_EncodedAbsUrl"];
		CXMLNode* dateCreatedAttr = [listNode attributeForName:@"ows_Created"];
		CXMLNode* dateModifiedAttr = [listNode attributeForName:@"ows_Modified"];
		CXMLNode* titleAttr = [listNode attributeForName:@"ows_Title"];
		CXMLNode* contentAttr = [listNode attributeForName:@"ows_ContentType"];
		if (titleAttr == nil) {
			titleAttr = [listNode attributeForName:@"ows_LinkFilename"];
		}
		NSMutableDictionary* dict = [NSMutableDictionary dictionary];
		if ([titleAttr stringValue]) {
			[dict setObject:[titleAttr stringValue] forKey:@"ows_Title"];
		}
		if ([nameAttr stringValue]) {
			[dict setObject:[nameAttr stringValue] forKey:@"ows_EncodedAbsUrl"];
		}
		if ([dateCreatedAttr stringValue]) {
			[dict setObject:[dateCreatedAttr stringValue] forKey:@"ows_Created"];
		}
		if ([dateModifiedAttr stringValue]) {
			[dict setObject:[dateModifiedAttr stringValue] forKey:@"ows_Modified"];
		}
		if ([contentAttr stringValue]) {
			[dict setObject:[contentAttr stringValue] forKey:@"ows_ContentType"];
		}
		[listDict setObject:dict forKey:[nameAttr stringValue]];
	}
	NSString* key = [keysArr objectAtIndex:indexPressed.row];
	SharepointListVC* slvc = [[SharepointListVC alloc] init];
	slvc.listsData = listDict;
	NSString* folderChoosed = [[listsData objectForKey:key] objectForKey:@"ows_Title"] ;
	slvc.currentFolder = [NSString stringWithFormat:@"%@%@/", currentFolder, folderChoosed];
	slvc.title = [[listsData objectForKey:key] objectForKey:@"ows_Title"];
	slvc.myListName = myListName;
	[self.navCntrl pushViewController:slvc animated:YES];
	[slvc release];
}
- (void) requestFinishedWithError: (NSError*) error {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[error description] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
}
- (void)dealloc {
    [super dealloc];
	[listsData release];
	[navCntrl release];
	[myListName release];
	[delegate release];
	[indexPressed release];
	[currentFolder release];
}

@end

@implementation SharepointListCell

@synthesize titleLbl, dateLbl, icon;

- (void)dealloc {
	[super dealloc];
	[titleLbl release];
	[dateLbl release];
	[icon release];
}

@end

