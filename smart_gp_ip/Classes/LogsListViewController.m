#import "LogsListViewController.h"
#import "LogListCell.h"
#import "LogScreenViewController.h"
#import <MessageUI/MessageUI.h>

@implementation LogsListViewController

- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"Logs list";
	UIBarButtonItem *rightButton = [[UIBarButtonItem alloc] initWithTitle:@"Export" 
																	style:UIBarButtonSystemItemDone 
																   target:self 
																   action:@selector(exportAction:)];
	self.navigationItem.rightBarButtonItem = rightButton;
}
- (void)mailComposeController:(MFMailComposeViewController*)controller  
          didFinishWithResult:(MFMailComposeResult)result 
                        error:(NSError*)error {
	if (result == MFMailComposeResultSent) {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Mail sent." 
														message:@"Sending the mail succeeded." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Sending the mail failed for unknown reason, try again later." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
	[self dismissModalViewControllerAnimated:YES];
}
- (void) exportAction: (id) sender {
	NSString* path = [LogScreenViewController getFilePath];
	NSArray* arrayOfLogs = [NSArray arrayWithContentsOfFile:path];
	NSString* body = [NSString stringWithString:@""];
	body = [self prepareBody: body withItems: arrayOfLogs];
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	controller.mailComposeDelegate = self;
	[controller setSubject:@"My Log"];
	[controller setMessageBody:body isHTML:NO]; 
	if (controller) [self presentModalViewController:controller animated:YES];
	[controller release];
}
- (NSString*) prepareBody: (NSString*) body withItems: (NSArray*) items {
	for (NSDictionary* dict in items) {
		for (NSString* key in [dict keyEnumerator]) {
			if (![key isEqualToString:@"Id"]) {
				NSString* value = [dict objectForKey:key];
				body = [body stringByAppendingString:key];
				body = [body stringByAppendingString:@": "];
				body = [body stringByAppendingString:value];
				body = [body stringByAppendingString:@"\n"];
			}
		}
		body = [body stringByAppendingString:@"\n"];
	}
	return body;
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 83.0;
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    NSArray* logs = [NSArray arrayWithContentsOfFile:[LogScreenViewController getFilePath]];
	return [logs count];
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	LogListCell *cell = nil;
    if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"LogListCell" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (LogListCell *) currentObject;
                break;
            }
        }
    }
	NSArray* logs = [NSArray arrayWithContentsOfFile:[LogScreenViewController getFilePath]];
	NSDictionary* log = [logs objectAtIndex:indexPath.row];
	cell->reminder = NO;
	cell.titleLbl.text = [log objectForKey:@"Title"];
	cell.detailLbl.text = [log objectForKey:@"Date"];
	cell.log = log;
	cell.navController = self.navigationController;
	cell.tvc = self;
	return cell;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
}
- (void)dealloc {
    [super dealloc];
}
- (void)viewWillAppear:(BOOL)animated {
	[super viewWillAppear:animated];
	[self.tableView reloadData];
}

@end

