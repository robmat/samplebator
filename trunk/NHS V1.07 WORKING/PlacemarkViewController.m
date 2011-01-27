
#import "PlacemarkViewController.h"


@implementation PlacemarkViewController

@synthesize placemark, tableView;

// used pressed the "Done" button
- (IBAction)done
{
    [self dismissModalViewControllerAnimated:YES];
}

- (void)viewWillAppear:(BOOL)animated
{
    [tableView reloadData];
}

// Customize the number of rows in the table view.
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return 9;
}

// Customize the appearance of table view cells.
- (UITableViewCell *)tableView:(UITableView *)table cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *kPlacemarkCellID = @"PlacemarkCell";
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:kPlacemarkCellID];
    if (cell == nil)
    {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle
                                       reuseIdentifier:kPlacemarkCellID] autorelease];
    }
    
    CGRect frame = cell.textLabel.frame;
    frame.size.width = 200;
    cell.textLabel.frame = frame;
    
    switch (indexPath.row)
    {
        case 0:
        {
            cell.detailTextLabel.text = @"Thoroughfare";
            cell.textLabel.text = placemark.thoroughfare;
        } break;
        case 1:
        {
            cell.detailTextLabel.text = @"Sub-thoroughfare";
            cell.textLabel.text = placemark.subThoroughfare;
        } break;
        case 2:
        {
            cell.detailTextLabel.text = @"Locality";
            cell.textLabel.text = placemark.locality;
        } break;
        case 3:
        {
            cell.detailTextLabel.text = @"Sub-locality";
            cell.textLabel.text = placemark.subLocality;
        } break;
        case 4:
        {
            cell.detailTextLabel.text = @"Administrative Area";
            cell.textLabel.text = placemark.administrativeArea;
        } break;
        case 5:
        {
            cell.detailTextLabel.text = @"Sub-administrative Area";
            cell.textLabel.text = placemark.subAdministrativeArea;
        } break;
        case 6:
        {
            cell.detailTextLabel.text = @"Postal Code";
            cell.textLabel.text = placemark.postalCode;

        } break;
        case 7:
        {
            cell.detailTextLabel.text = @"Country";
            cell.textLabel.text = placemark.country;
        } break;
        case 8:
        {
            cell.detailTextLabel.text = @"Country Code";
            cell.textLabel.text = placemark.countryCode;
        } break;
        default:
        {
            cell.textLabel.text = @"";
            cell.detailTextLabel.text = @"";
        } break;
    }
    
    return cell;
}

- (void)viewDidUnload
{
    self.tableView = nil;
}

- (void)dealloc
{
    [tableView release];
    [placemark release];
    
    [super dealloc];
}

@end

