#import "RemindersListViewController.h"
#import "LogListCell.h"

@implementation RemindersListViewController

- (void)viewDidLoad {
    [super viewDidLoad];
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    NSArray *notificationArray = [[UIApplication sharedApplication] scheduledLocalNotifications];
	return [notificationArray count];
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	LogListCell* cell = nil;
    if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"LogListCell" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (LogListCell *) currentObject;
                break;
            }
        }
    }
	cell->reminder = YES;
	NSArray *notificationArray = [[UIApplication sharedApplication] scheduledLocalNotifications];
	UILocalNotification* not = [notificationArray objectAtIndex: indexPath.row];
	cell.titleLbl.text = not.alertBody;
	NSDateFormatter* frmt = [[NSDateFormatter alloc] init];
	[frmt setDateFormat:@"dd-MM-yyyy"];
	cell.detailLbl.text = [frmt stringFromDate:not.fireDate];
	cell.locNot = not;
    return cell;
}
- (void)dealloc {
    [super dealloc];
}

@end