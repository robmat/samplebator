#import "LogListCell.h"
#import "LogScreenViewController.h"

@implementation LogListCell

@synthesize titleLbl, detailLbl, log, navController, tvc, locNot;

- (IBAction) editAction: (id) sender {
	LogScreenViewController* llvc = [[LogScreenViewController alloc] initWithNibName:nil bundle:nil];
	llvc.log = log;
	[self.navController pushViewController:llvc animated:YES];
	[llvc release];
}
- (IBAction) deleteAction: (id) sender {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Really delete?" 
													message:@"Are you sure?" 
												   delegate:self 
										  cancelButtonTitle:@"Cancel" 
										  otherButtonTitles:@"Yes", nil];
	[alert show];
	[alert release];
}
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
	if (buttonIndex == 1) {
		NSMutableArray* logs = [NSMutableArray arrayWithContentsOfFile:[LogScreenViewController getFilePath]];
		for (NSDictionary* logDict in logs) {
			NSNumber* time1 = [logDict objectForKey:@"Id"];
			NSNumber* time2 = [log objectForKey:@"Id"];
			if ([time1 isEqualToNumber:time2]) {
				[logs removeObject:logDict];
			}
		}
		[logs writeToFile:[LogScreenViewController getFilePath] atomically:YES];
		[tvc.tableView reloadData];
	}
}
- (void)dealloc {
	[titleLbl release];
	[detailLbl release];
	[log release];
	[locNot release];
	[tvc release];
	[super dealloc];
}

@end
